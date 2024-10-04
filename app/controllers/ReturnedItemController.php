<?php

namespace app\Controllers;

use app\Models\BorrowedItem;
use app\Models\Item;
use app\Models\ReturnedItem;
use core\Controller;
use core\Redirect;
use core\Response;
use core\View;
use Exception;

class ReturnedItemController extends Controller 
{

    public function __construct()
    {
        if (!authenticated()) {
            Redirect::to('not-found');
        }
    }
    
    public function index() {
        $returnedItems = ReturnedItem::leftJoin('borrowed_items', 'returned_items.borrowed_item_id', '=', 'borrowed_items.id')
            ->leftJoin('items', 'borrowed_items.item_id', '=', 'items.id')
            ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
            ->leftJoin('users', 'returned_items.user_id', '=', 'users.id')
            ->select([
                'returned_items.*',
                'items.name AS item_name',
                'items.id as item_id',
                'categories.name AS category_name',
                'users.name AS user_name',
                'borrowed_items.borrowed_date',
            ]);
            
        if (!admin()) {
            $returnedItems->where('returned_items.user_id', '=', userId());
        }

        $returnedItems = $returnedItems->paginate(10);
        
        $items = Item::all();

        View::render('returned-items/index', [
            'returnedItems' => $returnedItems,
            'items' => $items
        ]);
    }

    public function create($id) {
        try {
            ReturnedItem::create([
                'borrowed_item_id' => $id,
                'user_id' => userId(),
            ]);

            Response::json(['success' => true, 'message' => 'Item added successfully']);
        } catch (Exception $e) {
            Response::json(['success' => false, 'message' => 'Error adding returnedItem: ' . $e->getMessage()], 500);
        }
    }

    public function update($id) {
        try {
            $returnedItem = ReturnedItem::find($id);
            if ($returnedItem) {
                $borrowedItem = BorrowedItem::find($returnedItem['borrowed_item_id']);
                $item = Item::find($borrowedItem['item_id']);
                ReturnedItem::update($id, [
                    'returned_date' => $_POST['returned_date'],
                    'status' => $_POST['status'],
                ]);

                if ($_POST['status'] == 'approved') {
                    Item::update($item['id'], [
                        'quantity' => $item['quantity'] + 1,
                    ]);
                }

                Response::json(['success' => true, 'message' => 'Item updated successfully']);
            } else {
                Response::json(['success' => false, 'message' => 'Item not found'], 404);
            }
        } catch (Exception $e) {
            Response::json(['success' => false, 'message' => 'Error updating returnedItem: ' . $e->getMessage()], 500);
        }
    }

    public function delete($id) {
        try {
            $item = ReturnedItem::find($id);
            if ($item) {
                ReturnedItem::delete($id);
                Response::json(['success' => true, 'message' => 'Item deleted successfully']);
            } else {
                Response::json(['success' => false, 'message' => 'Item not found'], 404);
            }
        } catch (Exception $e) {
            Response::json(['success' => false, 'message' => 'Error deleting returnedItem: ' . $e->getMessage()], 500);
        }
    }
}