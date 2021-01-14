<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ItemController extends Controller
{
    const LIMIT = 25;

    public function index(Request $request)
    {
        $paginate = bcmul(self::LIMIT, ($request->get('paginate') - 1));

        $data = ['items' => Item::skip($paginate)->take(self::LIMIT)->get()];

        if (!$paginate) {
            $data['categories'] = Category::select('name', 'id')->get();
            $data['locations'] = Location::select('name', 'id')->get();
        }

        return response()->json($data);
    }

    public function store(Request $request)
    {
        Item::create([
            'name'          => $request->input('name'),
            'category_id'   => $request->input('category'),
            'location_id'   => $request->input('location'),
            'price'         => $request->input('price'),
        ]);
    }

    public function destroy(Item $item)
    {
        $item->delete();
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $item->name = $request->input('name');
        $item->category_id = $request->input('category');
        $item->location_id = $request->input('location');
        $item->price = $request->input('price');
        $item->save();
    }
}
