<?php

namespace app\controllers;

use app\models\Category;
use app\models\Item;
use core\Controller;
use core\QueryBuilder;
use core\Redirect;
use core\Response;
use core\View;
use Exception;

class ItemController extends Controller
{
    public function __construct()
    {
        if (!authenticated()) {
            Redirect::to('not-found');
        }
    }

    public function index()
    {
        $search = $_GET['search'] ?? null;

        $queryBuilder = new QueryBuilder();
        $items = $queryBuilder->getPaginatedItems($search);
        $categories = Category::orderBy('name')->get();

        View::render('items/index', [
            'items' => $items,
            'categories' => $categories,
            'search' => $search
        ]);
    }

    public function archive()
    {
        $search = $_GET['search'] ?? null;

        $queryBuilder = new QueryBuilder();
        $items = $queryBuilder->getPaginatedArchiveItems($search);
        $categories = Category::orderBy('name')->get();

        View::render('items/archive', ['items' => $items, 'categories' => $categories, 'search' => $search]);
    }

    public function create()
    {
        try {
            $name = $_POST['name'];
            $categoryId = $_POST['category_id'];
            $quantity = $_POST['quantity'];

            if (empty($name)) {
                Response::json(['success' => false, 'message' => 'Name is required'], 400);
            }

            $existingItem = Item::where('name', '=', $name)->where('category_id', '=', $categoryId)->withTrashed()->first();

            if ($existingItem && $existingItem['deleted_at'] !== null) {
                Response::json(['success' => false, 'message' => 'Item already exists in archive'], 400);
            }

            if (!$existingItem) {
                Item::create([
                    'name' => $name,
                    'category_id' => $categoryId,
                    'quantity' => $quantity
                ]);
            } else {
                Item::update($existingItem['id'], [
                    'quantity' => $existingItem['quantity'] + $quantity
                ]);
            }



            Response::json(['success' => true, 'message' => 'Item added successfully']);
        } catch (Exception $e) {
            Response::json(['success' => false, 'message' => 'Error adding item: ' . $e->getMessage()], 500);
        }
    }

    public function update($id)
    {
        try {
            $item = Item::find($id);
            if ($item) {

                Item::update($id, [
                    'name' => $_POST['name'],
                    'category_id' => $_POST['category_id'],
                    'quantity' => $_POST['quantity']
                ]);

                Response::json(['success' => true, 'message' => 'Item updated successfully']);
            } else {
                Response::json(['success' => false, 'message' => 'Item not found'], 404);
            }
        } catch (Exception $e) {
            Response::json(['success' => false, 'message' => 'Error updating item: ' . $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $item = Item::find($id);

            if ($item) {

                Item::delete($id);

                Response::json(['success' => true, 'message' => 'Item deleted successfully']);
            } else {
                Response::json(['success' => false, 'message' => 'Item not found'], 404);
            }
        } catch (Exception $e) {
            Response::json(['success' => false, 'message' => 'Error deleting item: ' . $e->getMessage()], 500);
        }
    }

    public function restore($id)
    {
        try {
            $item = Item::where('id', '=', $id)->withTrashed()->first();

            if ($item) {

                Item::restore($id);

                Response::json(['success' => true, 'message' => 'Item restored successfully']);
            } else {
                Response::json(['success' => false, 'message' => 'Item not found'], 404);
            }
        } catch (Exception $e) {
            Response::json(['success' => false, 'message' => 'Error restoring item: ' . $e->getMessage()], 500);
        }
    }
}
