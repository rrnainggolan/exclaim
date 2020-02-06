<?php

namespace App\Services;

use App\ExpenseClaim;
use App\Repositories\ExpenseClaimRepository;

class ExpenseClaimService
{
    public function createExpenseClaim(Array $data)
    {
        $expenseClaimRepository = new ExpenseClaimRepository();
        $expenseClaim = $expenseClaimRepository->createExpenseClaim($data);

        return $expenseClaim;
    }
}