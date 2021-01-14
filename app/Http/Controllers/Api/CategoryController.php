<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::get());
    }

    public function store(Request $request)
    {
        $category = Category::create([
            'name' => $request->input('name'),
            'parent_id' => $request->input('parentCategory')
        ]);
    }

    public function destroy(Category $category)
    {
        $category->items->each(function($item) {
            $item->delete();
        });

        $category->delete();
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->name = $request->input('name');
        $category->parent_id = $request->input('parentCategory');
        $category->save();
    }
}