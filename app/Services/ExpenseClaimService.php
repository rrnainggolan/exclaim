<?php

namespace App\Services;

use App\ExpenseClaim;
use App\Repositories\ExpenseClaimRepository;

class ExpenseClaimService
{
    public function getExpenseClaims()
    {
        $expenseClaims = ExpenseClaim::all();

        return $expenseClaims;
    }

    public function getExpenseClaimsByUserId($userId)
    {
        $expenseClaimRepository = new ExpenseClaimRepository();
        $expenseClaims = $expenseClaimRepository->getExpenseClaimsByUserId($userId);

        return $expenseClaims;
    }

    public function getExpenseClaim($id)
    {
        $expenseClaim = ExpenseClaim::find($id);

        return $expenseClaim;
    }

    public function createExpenseClaim(Array $data)
    {
        $expenseClaimRepository = new ExpenseClaimRepository();
        $expenseClaim = $expenseClaimRepository->createExpenseClaim($data);

        return $expenseClaim;
    }
}