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

class BorrowedItemController extends Controller 
{

    public function __construct()
    {
        if (!authenticated()) {
            Redirect::to('not-found');
        }
    }
    
    public function index() {
        $borrowedItems = BorrowedItem::leftJoin('items', 'borrowed_items.item_id', '=', 'items.id')
            ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
            ->leftJoin('users', 'borrowed_items.user_id', '=', 'users.id')
            ->leftJoin('returned_items', 'borrowed_items.id', '=', 'returned_items.borrowed_item_id')
            ->select([
                'borrowed_items.*',
                'items.name AS item_name',
                'categories.name AS category_name',
                'users.name AS user_name',
                'returned_items.id AS returned_id',
            ]);
            
        if (!admin()) {
            $borrowedItems->where('borrowed_items.user_id', '=', userId());
        }

        $borrowedItems = $borrowedItems->paginate(10);
            
        $items = Item::all();

        View::render('borrowed-items/index', [
            'borrowedItems' => $borrowedItems,
            'items' => $items
        ]);
    }

    public function create() {
        try {
            $itemId = $_POST['id'];
            $pendingBorrowedItem = $this->findPendingBorrowedItem($itemId);

            if ($pendingBorrowedItem) {
                Response::json(['success' => false, 'message' => 'You already have a pending request for this item'], 400);
            }

            $approvedBorrowedItem = $this->findApprovedBorrowedItem($itemId);

            if($approvedBorrowedItem) {
                $approvedReturnedItem = $this->findApprovedReturnedItem($approvedBorrowedItem['id']);

                if (!$approvedReturnedItem) {
                    Response::json(['success' => false, 'message' => 'You have not returned this item yet'], 400);
                }
            }

            BorrowedItem::create([
                'item_id' => $_POST['id'],
                'user_id' => userId(),
                'status' => 'pending'
            ]);

            Response::json(['success' => true, 'message' => 'Item added successfully']);
        } catch (Exception $e) {
            Response::json(['success' => false, 'message' => 'Error adding BorrowedItem: ' . $e->getMessage()], 500);
        }
    }

    public function update($id) {
        try {
            $borrowedItem = BorrowedItem::find($id);
            if (!$borrowedItem) {
                Response::json(['success' => false, 'message' => 'Item not found'], 404);
            }

            if ($_POST['status'] === 'approved') {
                $currentQuantity = $this->getItemQuantity($_POST['item_id']);
                $difference = $currentQuantity - 1;
                
                BorrowedItem::update($id, [
                    'item_id' => $_POST['item_id'],
                    'borrowed_date' => today(),
                    'status' => 'approved',
                    'borrowed_deadline' => $_POST['borrowed_deadline'],
                ]);

                $quantity = (int) $difference;

                Item::update($borrowedItem['item_id'], ['quantity' => $quantity]);
                Response::json(['success' => true, 'message' => 'Item approved successfully']);
                
            }

            if ($_POST['status'] === 'rejected') {
                BorrowedItem::update($id, [
                    'status' => 'rejected',
                ]);

                Response::json(['success' => true, 'message' => 'Item rejected successfully']);
            }
        } catch (Exception $e) {
            Response::json(['success' => false, 'message' => 'Error updating BorrowedItem: ' . $e->getMessage()], 500);
        }
    }

    public function delete($id) {
        try {
            $item = BorrowedItem::find($id);
            if ($item) {
                BorrowedItem::delete($id);
                Response::json(['success' => true, 'message' => 'Item deleted successfully']);
            } else {
                Response::json(['success' => false, 'message' => 'Item not found'], 404);
            }
        } catch (Exception $e) {
            Response::json(['success' => false, 'message' => 'Error deleting BorrowedItem: ' . $e->getMessage()], 500);
        }
    }

    private function getItemQuantity(int $itemId)
    {
        $item = Item::find($itemId);

        $quantity = $item['quantity'];

        if ($quantity < 1) {
            Response::json(['success' => false, 'message' => 'Item is not available'], 400);
        }

        return $item['quantity'];
    }

    private function findPendingBorrowedItem($id)
    {
        return BorrowedItem::where('item_id', '=', $id)
            ->where('user_id', '=', userId())
            ->where('status', '=', 'pending')
            ->first();
    }

    private function findApprovedBorrowedItem($id)
    {
        return BorrowedItem::where('item_id', '=', $id)
            ->where('user_id', '=', userId())
            ->where('status', '=', 'approved')
            ->first();
    }

    private function findApprovedReturnedItem($id)
    {
        return ReturnedItem::where('borrowed_item_id', '=', $id)
            ->where('user_id', '=', userId())
            ->where('status', '=', 'approved')
            ->first();
    }
}