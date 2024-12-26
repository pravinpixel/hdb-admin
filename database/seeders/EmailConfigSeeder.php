<?php

namespace Database\Seeders;

use App\Models\EmailConfiguration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;

class EmailConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmailConfiguration::create([
            'driver'            => 'smtp',
            'host'              => 'smtp.mailgun.org',
            'port'              => 587,
            'user_name'          => 'admin@cph.com',
            'password'          => Crypt::encryptString('test'),
            'encryption'        => 'tls',
            'sender_email'      => 'hello@cph.com', 
            'sender_name'       => 'cph'
        ]);
    }
}
