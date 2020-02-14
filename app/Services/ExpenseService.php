<?php

namespace App\Services;

use App\Expense;
use App\ExpenseAttachment;
use Carbon\Carbon;

class ExpenseService
{
    /**
     * Create expenses
     * 
     * 
     */
    public function createExpenses($expenseClaim, Array $expenses)
    {
        $createdAt = Carbon::now()->format('Y-m-d H:i:s');
        $updatedAt = Carbon::now()->format('Y-m-d H:i:s');

        foreach($expenses as $expense) {
            $expenseData = [
                'expense_claim_id' => $expenseClaim,
                'expense_type_id' => $expense['type'],
                'currency_id' => $expense['currency'],
                'amount' => $expense['amount'],
                'remarks' => $expense['remarks'],
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ];

            $createExpense = Expense::create($expenseData);
            $lastInsertedExpenseId = $createExpense->id;

            $i = 1;
            foreach($expense['file'] as $file) {
                $filename = 'exclaim-'.$expenseClaim.'-attachment-'.$i.'.'.$file->getClientOriginalExtension();
                
                $attachmentData = [
                    'expense_id' => $lastInsertedExpenseId,
                    'filename' => $filename
                ];
                ExpenseAttachment::create($attachmentData);

                $file->storeAs('attachments', $filename);
                $i++;
            }
        }
    }
}