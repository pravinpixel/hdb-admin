<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    public $guarded = [];
    
    use HasFactory;

    public function item() 
    {
    	return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
