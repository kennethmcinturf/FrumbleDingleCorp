<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReportController extends Controller
{
    public function productPriceReportData($price)
    {
        $results = Location::selectRaw(sprintf(
            'locations.name location_name,
            locations.id location_id,
            categories.name category_name,
            categories.id category_id,
            parent_categories.name AS parent_category_name,
            parent_categories.id parent_category_id,
            COALESCE(SUM(IF(items.price >= ? AND items.deleted_at IS NULL, 1, 0)), 0) count'
        ), [$price])
        ->join('items', function($join) {
            $join->on('items.location_id', '=', 'locations.id')
            ->whereNull('items.deleted_at');
        })
        ->join('categories', function($join) {
            $join->on('items.category_id', '=', 'categories.id')
            ->whereNull('categories.deleted_at');
        })
        ->leftJoin('categories AS parent_categories', function($join) {
            $join->on('categories.parent_id', '=', 'parent_categories.id')
            ->whereNull('parent_categories.deleted_at');
        })
        ->groupBy(
            'locations.name', 'categories.name', 'parent_categories.name', 'locations.id', 'categories.id', 'parent_categories.id'
        )->get();

        return response()->json($results);
    }

    public function productPriceReportItemsData(Request $request) 
    {
        $search = json_decode($request->get('data'), true);
        $prop = $request->get('prop');
        $price = $request->get('price');

        if ($prop == 'locations') {
            $items = Item::where('location_id', $search['location_id']);
        } else if ($prop == 'category') {
            $items = Item::where('category_id', $search['category_id']);
        } else {
            $items = Item::where('location_id', $search['location_id'])
            ->where('category_id', $search['category_id']);
        }

        $items = $items->where('price', '>=', $price);

        info($search);
        if (!$prop) {
            $items = $items->get()->filter(function($item) use ($search) {
                return $item->parentCategory()->value('id') == $search['parent_category_id'];
            });

            return $items;
        }

        return $items->with('category')->get();
    }
}
