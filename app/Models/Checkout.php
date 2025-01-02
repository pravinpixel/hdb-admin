<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    public $guarded = [];
    use HasFactory;

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id')->withTrashed();
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'checkout_by');
    }

    public function checkIn()
    {
        return $this->hasOne(ReturnItem::class,'checkout_id', 'id');
    }


}
