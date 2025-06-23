<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['name' => 'Fruits']);
        Category::create(['name' => 'Vegetables']);
        Category::create(['name' => 'Dairy']);
        Category::create(['name' => 'Bakery']);
        Category::create(['name' => 'Meat']);
    }
}
