<?php

namespace App\Console\Commands;

use App\Mail\UserRegistration;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserRegisterCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'userregister:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log:info("Cron Start!");
        User::chunk(50, function ($users) {
            foreach($users as $user) {
                $role = $user->roles()->first();
                if($role->name == 'admin') {
                    return false;
                }
                $credentials['password'] = $user->first_name.'@684452';
                Sentinel::update($user, $credentials);
                $details = [
                    'title'    => 'User Registration',
                    'user'     => $user,
                    'password' => $credentials['password'],
                    'role'     => $role,
                    'url'      => route('login')
                ];
                Mail::to( $user->email )->send(new UserRegistration( $details));
            }
        });
        $this->info('UserRegistrationEmail:Cron Cummand Run successfully!');
    }
}
