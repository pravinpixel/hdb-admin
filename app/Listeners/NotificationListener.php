<?php

namespace App\Listeners;

use App\Events\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Notification as Notify;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class NotificationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Notification  $event
     * @return void
     */
    public function handle(Notification $event)
    {
        return Notify::create([
            'created_by' => Sentinel::getUser()->id,
            'message'    => $event->notify->message,
            'type'       => $event->notify->type,
            'type_id'    => $event->notify->type_id ?? Null,
            'from'       => $event->notify->from ?? Null,
            'to'         => $event->notify->to ?? Null,
            'item_id'    => $event->notify->item_id ?? Null,
            'status'     => $event->notify->status
        ]);
    }
}
