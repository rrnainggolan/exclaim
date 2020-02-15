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
        'cash_advance',
        'description'
    ];

    protected $attributes = [
        'cash_advance' => 0
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function expenses()
    {
        return $this->hasMany('App\Expense');
    }

    public function expenseApprovers()
    {
        return $this->hasMany('App\ExpenseApprover');
    }
}
