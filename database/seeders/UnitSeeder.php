<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::create(['name' => 'kg']);
        Unit::create(['name' => 'g']);
        Unit::create(['name' => 'L']);
        Unit::create(['name' => 'ml']);
        Unit::create(['name' => 'pcs']);
    }
}
