<?php

use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parentCategory = null;

        foreach (range(1, 10) as $number) {
            $parentCategoryId = $parentCategory ? $parentCategory->id : null;
            $category = Category::create(['name' => 'Category '.$number, 'parent_id' => $parentCategoryId]);

            if ($number % 2 == 0) {
                $parentCategory = $category;
            }

            $location = Location::create(['name' => 'Location '.$number]);
            $itemNames = [];
            $itemName = rand(0, 100000);

            foreach (range(1, 3) as $itemNumber) {
                while(in_array($itemName, $itemNames)) {
                    $itemName = rand(0, 100000);
                }

                Item::create([
                    'name'          => 'Item '. $itemName,
                    'category_id'   => $category->id,
                    'location_id'   => $location->id,
                    'price'         => bcmul($itemNumber, rand(1, 4)),
                ]);

                $itemNames[] = $itemName;
            }
        }
    }
}
