<?php

namespace App\Console\Commands;

use App\Models\Notification;
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
            ->where('status','taken')
                                ->get();
            foreach($checkouts as $checkout) {
                Log::info($checkout->item->id);
                $checkout_date = strtotime($checkout->date_of_return);
                $current_date = strtotime(Date('Y-m-d'));
                $day_diff =  ( $checkout_date - $current_date ) / 86400 ;
                if($day_diff>0) {
             Log::info('overdue');
                $notify=new Notification();
                $notify->message="The ".$checkout->item->item_ref." is overdue. Kindly return it at your earliest convenience";
                $notify->created_by=1;
                $notify->item_id=$checkout->item_id;
                $notify->type="overdue";
                $notify->type_id=$checkout->id;
                $notify->save();     
                }
            }
            Log::info('overdue cron stop');
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }
}
