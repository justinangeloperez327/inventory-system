<?php

namespace core;

class Model
{
    protected $table;
    protected static $queryBuilder;

    public function __construct()
    {
        if (!self::$queryBuilder) {
            self::$queryBuilder = new QueryBuilder();
        }
        self::$queryBuilder->table($this->table);
    }

    // Handle static method calls
    public static function __callStatic($method, $args)
    {
        $instance = new static(); // Create an instance of the child class

        if (method_exists(self::$queryBuilder, $method)) {
            return call_user_func_array([self::$queryBuilder, $method], $args);
        }

        if (method_exists($instance, $method)) {
            return call_user_func_array([$instance, $method], $args);
        }

        throw new \Exception("Method $method does not exist in the model or query builder.");
    }

    // Allow querying through User::query() like in Laravel
    public static function query()
    {
        return new QueryBuilder();
    }

    // Simplified create method for inserting new records
    public static function create(array $data)
    {
        $instance = new static();
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return self::$queryBuilder->table($instance->table)->insert($data);
    }

    public static function update($id, array $data)
    {
        $instance = new static();
        $data['updated_at'] = date('Y-m-d H:i:s');
        return self::$queryBuilder->table($instance->table)->update($id, $data);
    }

    public static function delete($id)
    {
        $instance = new static();
        $data = ['deleted_at' => date('Y-m-d H:i:s')];
        return self::$queryBuilder->table($instance->table)->update($id, $data);
    }

    public static function restore($id)
    {
        $instance = new static();
        $data = ['deleted_at' => null];
        return self::$queryBuilder->table($instance->table)->update($id, $data);
    }

    public static function findBy($field, $value)
    {
        $instance = new static();
        return self::$queryBuilder->table($instance->table)
            ->where($field, '=', $value)
            ->first();
    }
}
