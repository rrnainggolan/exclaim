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

    /**
     * Create new expense Type
     * 
     * @return App\ExpenseType
     */
    public function createExpenseType($data)
    {
        $expenseType = ExpenseType::create($data);

        return $expenseType;
    }

    /**
     * Get specific Expense Type
     * 
     * @return App\ExpenseType
     */
    public function getExpenseType($id)
    {
        $expenseType = ExpenseType::find($id);

        return $expenseType;
    }

    /**
     * Update specific Expense Type
     * 
     * @return App\ExpenseType
     */
    public function updateExpenseType(ExpenseType $expenseType, $data)
    {
        $expenseType->update($data);

        return $expenseType;
    }

    public function deleteExpenseType(ExpenseType $expenseType)
    {
        $id = $expenseType->id;
        $expenseType->delete();

        return $id;
    }
}