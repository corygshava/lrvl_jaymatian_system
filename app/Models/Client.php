<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'created_by',
        'client_name',
        'client_contact',
        'client_gender',
        'payment_plan',
        'credit_balance',
        'kra_pin',
        'id_number',
        'extra_details',
    ];

    protected $casts = [
        'credit_balance' => 'decimal:2',
        'extra_details' => 'array',
    ];
}
