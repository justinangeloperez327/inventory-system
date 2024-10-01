<?php

namespace app\Controllers;

use app\Models\BorrowedItem;
use app\Models\Item;
use app\Models\RenewedItem;
use core\Redirect;
use core\Response;
use core\View;
use Exception;

class RenewedItemController
{
    public function __construct()
    {
        if (!authenticated()) {
            Redirect::to('not-found');
        }
    }
    
    public function index()
    {
        $renewedItems = RenewedItem::leftJoin('borrowed_items', 'renewed_items.borrowed_item_id', '=', 'borrowed_items.id')
            ->leftJoin('items', 'borrowed_items.item_id', '=', 'items.id')
            ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
            ->leftJoin('users', 'renewed_items.user_id', '=', 'users.id')
            ->select([
                'renewed_items.*',
                'items.name AS item_name',
                'borrowed_items.item_id as item_id',
                'categories.name AS category_name',
                'users.name AS user_name',
                'borrowed_items.borrowed_date',
                'borrowed_items.borrowed_deadline',
            ]);

        if (!admin()) {
            $renewedItems->where('renewed_items.user_id', '=', userId());
        }

        $renewedItems = $renewedItems->paginate(10);
        $items = Item::all();

        View::render('renewed-items/index', [
            'renewedItems' => $renewedItems,
            'items' => $items
        ]);
    }

    public function create($id) {
        try {
            $pendingRenewedItem = RenewedItem::where('borrowed_item_id', '=', $id)
                ->where('status', '=','pending')
                ->where('user_id', '=', userId())
                ->first();

            if ($pendingRenewedItem) {
                Response::json(['success' => false, 'message' => 'You already have a pending request for this item'], 400);
            }

            $approvedRenewedItem = RenewedItem::where('borrowed_item_id', '=', $id)
                ->where('status', '=','approved')
                ->where('user_id', '=', userId())
                ->first();

            if ($approvedRenewedItem) {
                Response::json(['success' => false, 'message' => 'You can only renew once per item'], 400);
            }

            RenewedItem::create([
                'borrowed_item_id' => $id,
                'status' => 'pending',
            ]);

            Response::json(['success' => true, 'message' => 'Item added successfully']);
        } catch (Exception $e) {
            Response::json(['success' => false, 'message' => 'Error adding returnedItem: ' . $e->getMessage()], 500);
        }
    }

    public function update($id) {
        try {
            $item = RenewedItem::find($id);
            if ($item) {
                if ($_POST['status'] === 'approved') {
                    BorrowedItem::update($item['borrowed_item_id'], [
                        'borrowed_deadline' => $_POST['borrowed_deadline'],
                        'status' => 'approved',
                    ]);

                    RenewedItem::update($id, [
                        'status' => 'approved',
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
}