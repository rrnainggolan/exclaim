<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseClaimsApproved extends Model
{
    protected $table = 'expense_claims_approved';

    protected $fillable = [
        'expense_claim_id',
        'user_id',
        'approved',
        'reason'
    ];

    public function expenseClaim()
    {
        return $this->belongsTo('App\ExpenseClaim');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
