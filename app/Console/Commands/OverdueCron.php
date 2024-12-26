<?php

namespace App\Console\Commands;

use App\Events\Notification;
use App\Mail\SendReminder;
use App\Models\Checkout;
use App\Models\Config;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OverdueCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'overdue:cron';

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
        try {
            $config = Config::find(1);
            Log::info('overdue cron start');
            $checkouts = Checkout::with(['item','user'])
                                ->where('return_status',0)
                                ->get();
            foreach($checkouts as $checkout) {
                $checkout_date = strtotime($checkout->date_of_return);
                $current_date = strtotime(Date('Y-m-d'));
                $day_diff =  ( $checkout_date - $current_date ) / 86400 ;
                if($day_diff < 0 && $day_diff <= -3) {
                    $email = $checkout->user->email;
                    $details = [
                        'title' => 'Overdue Email',
                        'user'  => $checkout->user,
                        'item'  => $checkout->item,
                        'overdue' => $day_diff
                    ];
                    if( $config->enable_email == 1) {
                        Mail::to($email)->send(new SendReminder($details));
                        if(count(Mail::failures()) > 0){
                            event(new Notification("Overdue email sent failure to [{$checkout->user->first_name}] [item name : {$checkout->item->item_name}]", "overdue_cron", null, null, null, $checkout->user->id, $checkout->item->id,0));
                            Log::info('Overdue email sent failure');
                        }else {
                            $config->last_cron_updated = now();
                            $config->save();
                            event(new Notification("Overdue email sent successfully to [{$checkout->user->first_name}] [item name : {$checkout->item->item_name}]", "overdue_cron", null, null, null, $checkout->user->id, $checkout->item->id));
                            Log::info('Overdue email sent successfully');
                        }
                    }
                }
            }
            Log::info('overdue cron stop');
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }
}
