<?php

namespace App\Models;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    public $guarded = [];
    
    use HasFactory, SoftDeletes;

  
    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    public function checkout()
    {
        return $this->hasOne(Checkout::class, 'item_id', 'id')->orderby('id', 'desc');
    }



}
