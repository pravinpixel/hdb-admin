<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Cartalyst\Sentinel\Roles\EloquentRole;

class Role extends EloquentRole
{

    protected $fillable = [
            'slug',
            'name',
            'permissions',
            'created_at',
            'updated_at',
    ];
}
