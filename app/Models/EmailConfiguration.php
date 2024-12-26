<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        "driver",
        "host",
        "port",
        "encryption",
        "user_name" ,
        "password",
        "sender_name",
        "sender_email"
    ];
}
