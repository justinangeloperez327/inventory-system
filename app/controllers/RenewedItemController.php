<?php

namespace app\controllers;

use app\models\BorrowedItem;
use app\models\Item;
use app\models\RenewedItem;
use core\QueryBuilder;
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
        $borrowedDate = $_GET['borrowed_date'] ?? null;

        $queryBuilder = new QueryBuilder();
        $renewedItems = $queryBuilder->getRenewedItems($borrowedDate);
        $items = Item::all();

        View::render('renewed-items/index', [
            'renewedItems' => $renewedItems,
            'items' => $items,
            'borrowedDate' => $borrowedDate,
        ]);
    }

    public function create($id)
    {
        try {
            $pendingRenewedItem = RenewedItem::where('borrowed_item_id', '=', $id)
                ->where('status', '=', 'pending')
                ->where('user_id', '=', userId())
                ->first();

            if ($pendingRenewedItem) {
                Response::json(['success' => false, 'message' => 'You already have a pending request for this item'], 400);
            }

            $approvedRenewedItem = RenewedItem::where('borrowed_item_id', '=', $id)
                ->where('status', '=', 'approved')
                ->where('user_id', '=', userId())
                ->first();

            if ($approvedRenewedItem) {
                Response::json(['success' => false, 'message' => 'You can only renew once per item'], 400);
            }

            RenewedItem::create([
                'borrowed_item_id' => $id,
                'status' => 'pending',
                'user_id' => userId(),
            ]);

            Response::json(['success' => true, 'message' => 'Item added successfully']);
        } catch (Exception $e) {
            Response::json(['success' => false, 'message' => 'Error adding returnedItem: ' . $e->getMessage()], 500);
        }
    }

    public function update($id)
    {
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

                if ($_POST['status'] === 'pending') {

                    RenewedItem::update($id, [
                        'status' => 'pending',
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
