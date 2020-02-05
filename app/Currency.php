<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        'code',
        'name'
    ];

    public function expenses()
    {
        return $this->hasMany('App\Expense');
    }
}
