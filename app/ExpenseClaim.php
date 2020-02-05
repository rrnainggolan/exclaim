<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseClaim extends Model
{
    protected $fillable = [
        'code',
        'user_id',
        'start_date',
        'end_date',
        'cash_advance'
    ];

    protected $attributes = [
        'cash_advance' => 0
    ];
}
