<?php

namespace core;

use PDO;

class QueryBuilder
{
    protected $pdo;
    protected $table;
    protected $conditions = [];
    protected $bindings = [];
    protected $selectFields = '*';
    protected $order = '';
    protected $limit = '';
    protected $offset = '';
    protected $joins = [];
    protected $withTrashed = false; // For including soft-deleted records

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    // Add whereNull method to handle IS NULL conditions
    public function whereNull($field)
    {
        // If field doesn't contain a table reference, prepend main table name
        if (strpos($field, '.') === false) {
            $field = $this->table . '.' . $field;
        }
        $this->conditions[] = "$field IS NULL";
        return $this;
    }

    public function where($field, $operator, $value)
    {
        // If field doesn't contain a table reference, prepend main table name
        if (strpos($field, '.') === false) {
            $field = $this->table . '.' . $field;
        }
        $this->conditions[] = "$field $operator ?";
        $this->bindings[] = $value;

        return $this;
    }

    public function select($fields)
    {
        // Handle if select is passed as an array or a string
        $this->selectFields = is_array($fields) ? implode(',', $fields) : $fields;
        
        return $this;
    }

    public function leftJoin($table, $first, $operator, $second)
    {
        $this->joins[] = "LEFT JOIN $table ON $first $operator $second";
        return $this;
    }

    public function rightJoin($table, $first, $operator, $second)
    {
        $this->joins[] = "RIGHT JOIN $table ON $first $operator $second";
        return $this;
    }

    public function orderBy($field, $direction = 'ASC')
    {
        $this->order = " ORDER BY $field $direction";
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = " LIMIT $limit";
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = " OFFSET $offset";
        return $this;
    }

    public function get()
    {
        $sql = "SELECT $this->selectFields FROM $this->table";

        if (!empty($this->joins)) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        // Add conditions
        if (!empty($this->conditions)) {
            $sql .= " WHERE " . implode(' AND ', $this->conditions);
        }

        // Exclude soft-deleted records unless withTrashed() is used
        if (!$this->withTrashed) {
            $sql .= (empty($this->conditions) ? ' WHERE ' : ' AND ') . "$this->table.deleted_at IS NULL";
        }

        // Append order, limit, and offset
        $sql .= $this->order . $this->limit . $this->offset;

        // Prepare and execute the query
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->reset();

        return $result;
    }

    public function first()
    {
        $this->limit(1); // Fetch only one row
        $result = $this->get();
        return $result ? $result[0] : null;
    }

    public function find($id)
    {
        return $this->where('id', '=', $id)->first();
    }

    public function insert($data)
    {
        $fields = implode(',', array_keys($data));
        $placeholders = rtrim(str_repeat('?,', count($data)), ',');

        $sql = "INSERT INTO $this->table ($fields) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($data));

        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $fields = '';
        $this->bindings = []; // Reset bindings to avoid conflicts
        foreach ($data as $key => $value) {
            $fields .= "$key = ?,"; 
            $this->bindings[] = $value;
        }
        $fields = rtrim($fields, ',');

        $sql = "UPDATE $this->table SET $fields WHERE id = ?";
        $this->bindings[] = $id;

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($this->bindings);
    }

    public function all()
    {
        return $this->get();
    }

    // Soft delete function: sets deleted_at to NOW()
    public function softDelete($id)
    {
        $sql = "UPDATE $this->table SET deleted_at = NOW() WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Restore function: sets deleted_at to NULL
    public function restore($id)
    {
        $sql = "UPDATE $this->table SET deleted_at = NULL WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Resets query state after each execution
    private function reset()
    {
        $this->table = null;
        $this->conditions = [];
        $this->bindings = [];
        $this->selectFields = '*';
        $this->order = '';
        $this->limit = '';
        $this->offset = '';
        $this->joins = [];
        $this->withTrashed = false;
    }

    public function paginate($perPage = 10, $pageParam = 'page')
    {
        // Get the current page from the query string (e.g., ?page=2)
        $currentPage = isset($_GET[$pageParam]) ? (int)$_GET[$pageParam] : 1;
        $currentPage = max($currentPage, 1); // Ensure it's at least 1

        // Calculate the total number of records
        $totalRecords = $this->count();
        $totalPages = ceil($totalRecords / $perPage);

        // Make sure the current page doesn't exceed the total number of pages
        if ($currentPage > $totalPages && $totalPages > 0) {
            $currentPage = $totalPages;
        }

        // Calculate the offset for the current page
        $offset = ($currentPage - 1) * $perPage;

        // Fetch the paginated results
        $this->limit($perPage)->offset($offset);
        $results = $this->get();

        // Generate pagination URLs
        $previousPageUrl = $this->generatePageUrl($currentPage - 1, $pageParam);
        $nextPageUrl = $this->generatePageUrl($currentPage + 1, $pageParam);

        // Return the paginated data and metadata
        return [
            'data' => $results,
            'total' => $totalRecords,
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'previous_page_url' => ($currentPage > 1) ? $previousPageUrl : null,
            'next_page_url' => ($currentPage < $totalPages) ? $nextPageUrl : null,
            'start_item' => ($offset + 1),
            'end_item' => min($offset + $perPage, $totalRecords),
        ];
    }

    private function generatePageUrl($page, $pageParam)
    {
        $queryParams = $_GET;
        $queryParams[$pageParam] = $page;
        return '?' . http_build_query($queryParams);
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) as count FROM $this->table";

        if (!empty($this->conditions)) {
            $sql .= " WHERE " . implode(' AND ', $this->conditions);
        }

        // Exclude soft-deleted records unless withTrashed() is used
        if (!$this->withTrashed) {
            $sql .= (empty($this->conditions) ? ' WHERE ' : ' AND ') . "$this->table.deleted_at IS NULL";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['count'];
    }

    // Include soft deleted records in the query
    public function withTrashed()
    {
        $this->withTrashed = true;
        return $this;
    }

    public function raw($sql, $bindings = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function rawWithPagination($sql, $bindings = [], $perPage = 10, $pageParam = 'page')
    {
        // Calculate total records
        $countSql = "SELECT COUNT(*) as count FROM ($sql) as subquery";
        $stmt = $this->pdo->prepare($countSql);
        $stmt->execute($bindings);
        $totalRecords = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Calculate pagination details
        $currentPage = isset($_GET[$pageParam]) ? (int)$_GET[$pageParam] : 1;
        $currentPage = max($currentPage, 1);
        $totalPages = ceil($totalRecords / $perPage);
        $offset = ($currentPage - 1) * $perPage;

        // Modify the original SQL to include LIMIT and OFFSET
        $paginatedSql = $sql . " LIMIT $perPage OFFSET $offset";
        $stmt = $this->pdo->prepare($paginatedSql);
        $stmt->execute($bindings);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Generate pagination URLs
        $previousPageUrl = $this->generatePageUrl($currentPage - 1, $pageParam);
        $nextPageUrl = $this->generatePageUrl($currentPage + 1, $pageParam);

        return [
            'data' => $results,
            'total' => $totalRecords,
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'previous_page_url' => ($currentPage > 1) ? $previousPageUrl : null,
            'next_page_url' => ($currentPage < $totalPages) ? $nextPageUrl : null,
            'start_item' => ($offset + 1),
            'end_item' => min($offset + $perPage, $totalRecords),
        ];
    }
}
