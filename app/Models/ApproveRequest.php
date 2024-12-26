<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApproveRequest extends Model
{
    public $guarded = [];
    
    use HasFactory;

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'requested_by');
    }

    public function rejectedBy()
    {
        return $this->hasOne(User::class, 'id', 'rejected_by');
    }

    public function approvedBy()
    {
        return $this->hasOne(User::class, 'id', 'approved_by');
    }
}
