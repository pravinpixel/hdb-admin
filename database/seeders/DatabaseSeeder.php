<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ConfigSeeder::class);
        $this->call(CategorySeed::class);
        $this->call(GenreSeed::class);
        $this->call(TypeSeed::class);
        $this->call(EmailConfigSeeder::class);
    }
}
