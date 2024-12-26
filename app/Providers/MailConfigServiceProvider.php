<?php

namespace App\Providers;


use App\Models\EmailConfiguration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
      
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $configuration = Cache::remember('emailConfig',60, function () {
            return EmailConfiguration::first();   
        });
        
        if(!is_null($configuration)) {
            Log::info('email config called');
            $config = array(
                'driver'     =>     $configuration->driver,
                'host'       =>     $configuration->host,
                'port'       =>     $configuration->port,
                'username'   =>     $configuration->user_name,
                'password'   =>     !($configuration->password == '') ? Crypt::decryptString($configuration->password) : '',
                'encryption' =>     $configuration->encryption,
                'from'       =>     array('address' => $configuration->sender_email, 'name' => $configuration->sender_name),
            );  
            Config::set('mail',$config);
        }
           
    }
    
}
