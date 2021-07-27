<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExpenseClaimRepository
{
    /**
     * Get SQL Query for expense claims
     * 
     * @return Illuminate\Database\Query\Builder
     */
    private function getExpenseClaimsQuery($userId)
    {
        $expenseClaimsQuery = DB::table('expense_claims')
            ->select([
                'expense_claims.*',
                // DB::raw('SUM(DISTINCT expenses.amount) AS amount_total'),
                DB::raw('SUM(expenses.amount) AS amount_total'),
                // DB::raw('SUM(DISTINCT expense_claims_approved.approved) AS total_approved'),
                DB::raw('SUM(expense_claims_approved.approved) AS total_approved'),
                DB::raw('users.name AS user_name'),
                DB::raw('MAX(expense_claims_approved.user_id) AS approver_id')
            ])
            ->leftJoin('expenses', 'expense_claims.id', '=', 'expenses.expense_claim_id')
            ->leftJoin('expense_claims_approved', 'expense_claims.id', '=', 'expense_claims_approved.expense_claim_id')
            ->leftJoin('users', 'expense_claims.user_id', '=', 'users.id')
            ->groupBy('expenses.expense_claim_id', 'expense_claims.id');

        if($userId) {
            $expenseClaimsQuery->where('expense_claims.user_id', '=', $userId);
        }

        return $expenseClaimsQuery;
    }

    /**
     * Get all pending expense claims
     * 
     * @return Illuminate\Support\Collection
     */
    public function getActiveExpenseClaims($userId=NULL) 
    {
        $query = $this->getExpenseClaimsQuery($userId);
        $expenseClaims = $query->havingRaw(
            'COUNT(DISTINCT expense_claims_approved.user_id) < ? AND (total_approved != 0  OR total_approved is null)', [2]
        )->get();

        return $expenseClaims;
    }

    /**
     * Get all completed expense claims by specific user
     * 
     * @return Illuminate\Support\Collection
     */
    public function getCompletedExpenseClaims($userId=NULL) 
    {
        $query = $this->getExpenseClaimsQuery($userId);
        $expenseClaims = $query->havingRaw(
            'COUNT(DISTINCT expense_claims_approved.user_id) >= ? OR total_approved = 0', [2]
        )->get();

        return $expenseClaims;
    }

    /**
     * Get all approved expense claims by specific user
     * 
     * @return Illuminate\Support\Collection
     */
    public function getApprovedExpenseClaims($userId=NULL) 
    {
        $query = $this->getExpenseClaimsQuery($userId);
        $expenseClaims = $query->havingRaw(
            'COUNT(DISTINCT expense_claims_approved.user_id) >= ?', [2]
        )->get();

        return $expenseClaims;
    }
}