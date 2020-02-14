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
            if(!$expense['type'] || !$expense['amount']) {
                continue;
            }

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

            if(array_key_exists('file', $expense)) {
                $i = 1;
                foreach($expense['file'] as $file) {
                    $ext = $file->getClientOriginalExtension();
                    $filename = 'exc-'.$expenseClaim.'-exp-'.$lastInsertedExpenseId.'-att-'.$i.'.'.$ext;
                    
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
}