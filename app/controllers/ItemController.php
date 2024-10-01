<?php

namespace app\Controllers;

use app\Models\Category;
use app\Models\Item;
use core\Controller;
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
    
    public function index() {
        $items = Item::leftJoin('categories', 'items.category_id', '=', 'categories.id')
            ->select(['items.*', 'categories.name AS category_name'])
            ->orderBy('id', 'desc')
            ->paginate(10);
        
        $categories = Category::orderBy('name')->get();

        View::render('items/index', ['items' => $items, 'categories' => $categories]);
    }

    public function create() {
        try {
            Item::create([
                'name' => $_POST['name'],
                'category_id' => $_POST['category_id'],
                'quantity' => $_POST['quantity']
            ]);

            Response::json(['success' => true, 'message' => 'Item added successfully']);
        } catch (Exception $e) {
            Response::json(['success' => false, 'message' => 'Error adding item: ' . $e->getMessage()], 500);
        }
    }

    public function update($id) {
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

    public function delete($id) {
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
}