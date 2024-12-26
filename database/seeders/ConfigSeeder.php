<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Config::create(['item_prefix' => 'AAA', 'item_number' => '1001', 'last_cron_updated' => now() ]);
    }
}
