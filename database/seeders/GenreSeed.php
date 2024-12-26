<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenreSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Genre::create(['genre_name' => 'Manual', 'is_active' => 1]);
        Genre::create(['genre_name' => 'Consumable', 'is_active' => 1]);
    }
}
