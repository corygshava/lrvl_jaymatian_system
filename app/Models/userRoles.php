<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class userRoles extends Model
{
    public $protected = [
        'role_name',
        'role_permissions',
    ];

    protected $casts = [
        'role_permissions' => 'array',
    ];
}
