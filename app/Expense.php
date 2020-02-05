<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'expense_claim_id',
        'expense_type_id',
        'currency_id',
        'date',
        'amount',
        'remarks'
    ];

    public function expenseClaim()
    {
        return $this->belongsTo('App\ExpenseClaim');
    }

    public function expenseType()
    {
        return $this->belongsTo('App\ExpenseType');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }

    public function expenseAttachments()
    {
        return $this->hasMany('App\ExpenseAttachment');
    }
}
