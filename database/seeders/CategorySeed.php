<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $manual = Category::create(['category_name' => 'Manual', 'is_active' => 1]);
        $consumable = Category::create(['category_name' => 'Consumable', 'is_active' => 1]);
        $manual->subcategory()->create(['subcategory_name' => 'Manual','is_active' => 1]);
        $consumable->subcategory()->create(['subcategory_name' => 'Consumable','is_active' => 1]);
    }
}
