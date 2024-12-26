<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Notification as Notify;

class Notification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $message;
    public $created_by;
    public $type;
    public $type_id;
    public $from;
    public $to;
    public $item_id;
    public $status;
    public function __construct($message, $type, $type_id = null, $from = null, $to = null, $item_id = null, $status=1 )
    {
        $notify = new Notify();
        $notify->message = $message;
        $notify->type = $type;
        $notify->type_id = $type_id;
        $notify->from = $from;
        $notify->to = $to;
        $notify->item_id = $item_id;
        $notify->status = $status;
        $this->notify =  $notify;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
