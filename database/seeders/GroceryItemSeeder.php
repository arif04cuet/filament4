<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\GroceryItem;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class GroceryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fruitCategory = Category::where('name', 'Fruits')->first();
        $vegetableCategory = Category::where('name', 'Vegetables')->first();
        $dairyCategory = Category::where('name', 'Dairy')->first();

        $kgUnit = Unit::where('name', 'kg')->first();
        $pcsUnit = Unit::where('name', 'pcs')->first();
        $lUnit = Unit::where('name', 'L')->first();

        GroceryItem::create([
            'name' => 'Apples',
            'category_id' => $fruitCategory->id,
            'unit_id' => $kgUnit->id,
        ]);

        GroceryItem::create([
            'name' => 'Bananas',
            'category_id' => $fruitCategory->id,
            'unit_id' => $pcsUnit->id,
        ]);

        GroceryItem::create([
            'name' => 'Carrots',
            'category_id' => $vegetableCategory->id,
            'unit_id' => $kgUnit->id,
        ]);

        GroceryItem::create([
            'name' => 'Milk',
            'category_id' => $dairyCategory->id,
            'unit_id' => $lUnit->id,
        ]);
    }
}
