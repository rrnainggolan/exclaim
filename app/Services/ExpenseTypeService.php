<?php

namespace App\Services;

use App\ExpenseType;

class ExpenseTypeService
{
    /**
     * Get all expense types
     * 
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getExpenseTypes()
    {
        $expenseTypes = ExpenseType::all();

        return $expenseTypes;
    }
}