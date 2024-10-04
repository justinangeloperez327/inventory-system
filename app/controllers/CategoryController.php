<?php

namespace app\Controllers;

use app\Models\Category;
use core\Controller;
use core\Redirect;
use core\Response;
use core\View;
use Exception;

class CategoryController extends Controller 
{
    public function __construct()
    {
        if (!authenticated()) {
            Redirect::to('not-found');
        }
    }
    
    public function index() {
        $categories = Category::leftJoin('categories as parent', 'categories.parent_id', '=', 'parent.id')
            ->select(['categories.*', 'parent.name AS parent_name'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        $parents = Category::all();

        View::render('categories/index', ['categories' => $categories, 'parents' => $parents]);
    }

    public function create() {
        try {
            $name = $_POST['name'];
            $parentId = $_POST['parent_id'] ? $_POST['parent_id'] : null;

            Category::create([
                'name' => $name,
                'parent_id' => $parentId,
            ]);

            Response::json(['success' => true, 'message' => 'Category added successfully']);
        } catch (Exception $e) {
            Response::json(['success' => false, 'message' => 'Error adding item: ' . $e->getMessage()], 500);
        }
    }

    public function update($id) {
        try {
            $item = Category::find($id);
            if ($item) {

                $name = $_POST['name'];
                $parentId = $_POST['parent_id'] ? $_POST['parent_id'] : null;

                Category::update($id, [
                    'name' => $name,
                    'parent_id' => $parentId,
                ]);

                Response::json(['success' => true, 'message' => 'Category updated successfully']);
            } else {
                Response::json(['success' => false, 'message' => 'Category not found'], 404);
            }
        } catch (Exception $e) {
            Response::json(['success' => false, 'message' => 'Error updating item: ' . $e->getMessage()], 500);
        }
    }

    public function delete($id) {
        try {
            $item = Category::find($id);
            if ($item) {
                Category::delete($id);
                Response::json(['success' => true, 'message' => 'Category deleted successfully']);
            } else {
                Response::json(['success' => false, 'message' => 'Category not found'], 404);
            }
        } catch (Exception $e) {
            Response::json(['success' => false, 'message' => 'Error deleting item: ' . $e->getMessage()], 500);
        }
    }
}