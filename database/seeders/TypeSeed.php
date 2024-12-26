<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Type::create(['type_name' => 'Book', 'is_active'=> 1]);
        Type::create(['type_name' => 'Testkit', 'is_active'=> 1]);
    }
}
