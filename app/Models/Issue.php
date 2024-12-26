<?php

namespace App\Models;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    public $guarded = [];

    use HasFactory;

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function checkouts()
    {
        return $this->hasMany(Checkout::class);
    }
}
