<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExpenseClaimRepository
{
    /**
     * Get SQL Query for expense claims by specific user
     * 
     * @return Illuminate\Database\Query\Builder
     */
    private function getExpenseClaimsQueryByUserId($userId)
    {
        $expenseClaimsQuery = DB::table('expense_claims')
            ->select([
                'expense_claims.*',
                DB::raw('SUM(DISTINCT expenses.amount) AS amount_total'),
                DB::raw('SUM(expense_claims_approved.approved) AS total_approved')
            ])
            ->leftJoin('expenses', 'expense_claims.id', '=', 'expenses.expense_claim_id')
            ->leftJoin('expense_claims_approved', 'expense_claims.id', '=', 'expense_claims_approved.expense_claim_id')
            ->where('expense_claims.user_id', '=', $userId)
            ->groupBy('expenses.expense_claim_id', 'expense_claims.id');

        return $expenseClaimsQuery;
    }

    /**
     * Get all pending expense claims by specific user
     * 
     * @return Illuminate\Support\Collection
     */
    public function getExpenseClaimsPendingByUserId($userId) 
    {
        $query = $this->getExpenseClaimsQueryByUserId($userId);
        $expenseClaims = $query->havingRaw('COUNT(expense_claims_approved.user_id) < ?', [2])->get();

        return $expenseClaims;
    }

    /**
     * Get all completed expense claims by specific user
     * 
     * @return Illuminate\Support\Collection
     */
    public function getExpenseClaimsCompletedByUserId($userId) 
    {
        $query = $this->getExpenseClaimsQueryByUserId($userId);
        $expenseClaims = $query->havingRaw('COUNT(expense_claims_approved.user_id) >= ?', [2])->get();

        return $expenseClaims;
    }

    /**
     * Get all expense claims by specific user
     * 
     * @return Illuminate\Support\Collection
     */
    public function getExpenseClaimsByUserId($userId)
    {
        $query = $this->getExpenseClaimsQueryByUserId($userId);
        $expenseClaims = $query->get();

        return $expenseClaims;
    }
}