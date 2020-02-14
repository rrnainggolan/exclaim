<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseAttachment extends Model
{
    protected $fillable = [
        'expense_id',
        'filename'
    ];

    public function expense()
    {
        return $this->belongsTo('App\Expense');
    }
}
