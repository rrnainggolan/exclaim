<?php

namespace App\Services;

use App\ExpenseClaim;
use App\Repositories\ExpenseClaimRepository;

class ExpenseClaimService
{
    /**
     * Get all expense claims
     * 
     * @return App\ExpenseClaim
     */
    public function getExpenseClaims()
    {
        $expenseClaims = ExpenseClaim::all();

        return $expenseClaims;
    }

    /**
     * Get all expense claims by specific user
     * 
     * @return Illuminate\Support\Collection
     */
    public function getExpenseClaimsByUserId($userId)
    {
        $expenseClaimRepository = new ExpenseClaimRepository();
        $expenseClaims = $expenseClaimRepository->getExpenseClaimsByUserId($userId);

        return $expenseClaims;
    }

    /**
     * Get all pending expense claims by specific user
     * 
     * @return Illuminate\Support\Collection
     */
    public function getExpenseClaimsPendingByUserId($userId)
    {
        $expenseClaimRepository = new ExpenseClaimRepository();
        $expenseClaims = $expenseClaimRepository->getExpenseClaimsPendingByUserId($userId);

        return $expenseClaims;
    }

    /**
     * Get all completed expense claims by specific user
     * 
     * @return Illuminate\Support\Collection
     */
    public function getExpenseClaimsCompletedByUserId($userId)
    {
        $expenseClaimRepository = new ExpenseClaimRepository();
        $expenseClaims = $expenseClaimRepository->getExpenseClaimsCompletedByUserId($userId);

        return $expenseClaims;
    }

    /**
     * Get specific expense claim
     * 
     * @return App\ExpenseClaim
     */
    public function getExpenseClaim($id)
    {
        $expenseClaim = ExpenseClaim::find($id);

        return $expenseClaim;
    }

    /**
     * Create expense claim
     * Returning the inserted id
     * 
     * return int
     */
    public function createExpenseClaim(Array $data)
    {
        $expenseClaim = ExpenseClaim::create($data);

        return $expenseClaim;
    }
}