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

    public function getExpenseClaimsById($userId)
    {
        $expenseClaims = ExpenseClaim::where('user_id', $userId)
            ->get();

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