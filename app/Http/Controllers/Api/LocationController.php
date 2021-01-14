<?php

namespace App\Http\Controllers\Api;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LocationController extends Controller
{
    public function index()
    {
        return response()->json(Location::get());
    }

    public function store(Request $request)
    {
        Location::create(['name' => $request->input('name')]);
    }

    public function destroy(Location $location)
    {
        $location->items->each(function($item) {
            $item->delete();
        });

        $location->delete();
    }

    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);
        $location->name = $request->input('name');
        $location->save();
    }
}