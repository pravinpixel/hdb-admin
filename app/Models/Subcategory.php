<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subcategory extends Model
{
    use HasFactory,SoftDeletes;

    public function item()
    {
        return $this->hasOne(Item::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
