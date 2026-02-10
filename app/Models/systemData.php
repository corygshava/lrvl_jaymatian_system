<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class systemData extends Model
{
    protected $fillable = [
        'item_name',
        'item_data',
    ];

    protected $casts = [
        'item_data' => 'array'
    ];
}
