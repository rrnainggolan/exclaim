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
                DB::raw('COUNT(expense_claims_approved.user_id) AS total_approved')
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

    /**
     * Create Expense Claim by using database transactions
     */
    public function createExpenseClaim($data)
    {
        $expenseClaimId = null;

        // Database transaction
        DB::transaction(function() use(&$expenseClaimId, $data) {
            $createdAt = Carbon::now()->format('Y-m-d H:i:s');
            $updatedAt = Carbon::now()->format('Y-m-d H:i:s');

            if(!$data['cash_advance']) {
                $cashAdvance = 0;
            } else {
                $cashAdvance = $data['cash_advance'];
            }

            $expenseClaimData = [
                'code' => $data['code'],
                'user_id' => $data['user_id'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'cash_advance' => $cashAdvance,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ];

            // Insert expense claim record
            $expenseClaimId = DB::table('expense_claims')->insertGetId($expenseClaimData);

            $expensesData = [];
            foreach($data['expenses'] as $expense) {
                if($expense['type']) {
                    $expenseData = [
                        'expense_claim_id' => $expenseClaimId,
                        'expense_type_id' => $expense['type'],
                        'currency_id' => $expense['currency'],
                        'amount' => $expense['amount'],
                        'remarks' => $expense['remarks'],
                        'created_at' => $createdAt,
                        'updated_at' => $updatedAt
                    ];
    
                    array_push($expensesData, $expenseData);
                }
            }

            // Insert expenses
            $expenses = DB::table('expenses')->insert($expensesData);

        });

        return $expenseClaimId;
    }
}