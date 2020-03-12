<?php

namespace App\Services;

use App\ExpenseClaim;
use App\Repositories\ExpenseClaimRepository;
use App\ExpenseClaimsApproved;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ExpenseClaimRequested;
use App\Notifications\ExpenseClaimApproved;
use App\Notifications\ExpenseClaimRejected;
use App\Notifications\ExpenseClaimRequestedTelegram;
use App\User;

class ExpenseClaimService
{
    /**
     * Get all pending expense claims
     * that belong to logged in user
     * 
     * @return Illuminate\Support\Collection
     */
    public function getMyPendingExpenseClaims()
    {
        $expenseClaimRepository = new ExpenseClaimRepository();
        $expenseClaims = $expenseClaimRepository->getActiveExpenseClaims(Auth::user()->id);

        return $expenseClaims;
    }

    /**
     * Get all completed expense claims
     * that belong to logged in user
     * 
     * @return Illuminate\Support\Collection
     */
    public function getMyCompletedExpenseClaims()
    {
        $expenseClaimRepository = new ExpenseClaimRepository();
        $expenseClaims = $expenseClaimRepository->getCompletedExpenseClaims(Auth::user()->id);

        return $expenseClaims;
    }

    /**
     * Get all active expense claims
     * 
     * @return Array
     */
    public function getActiveExpenseClaims()
    {
        $data = [];
        $data['approved'] = [];
        $expenseClaimRepository = new ExpenseClaimRepository();
        $expenseClaims = $expenseClaimRepository->getActiveExpenseClaims();

        foreach($expenseClaims as $key => $value) {
            if($value->approver_id === Auth::user()->id || $value->user_id === Auth::user()->id) {
                $expenseClaims->forget($key);
                if($value->user_id != Auth::user()->id) {
                    $data['approved'][] = $value;
                }
            }
        }

        $data['unapproved'] = $expenseClaims;

        return $data;
    }

    /**
     * Get all completed expense claims
     * 
     * @return Illuminate\Support\Collection
     */
    public function getCompletedExpenseClaims()
    {
        $expenseClaimRepository = new ExpenseClaimRepository();
        $completedExpenseClaims = $expenseClaimRepository->getCompletedExpenseClaims();

        return $completedExpenseClaims;
    }

    /**
     * Get all approved expense claims
     * 
     * @return Illuminate\Support\Collection
     */
    public function getApprovedExpenseClaims()
    {
        $expenseClaimRepository = new ExpenseClaimRepository();
        $approvedExpenseClaims = $expenseClaimRepository->getApprovedExpenseClaims();

        return $approvedExpenseClaims;
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

        // Check if requester is Admin
        $role = Auth::user()->roles[0]->name;
        if($role == 'admin') {
            $data = [
                'expense_claim_id' => $expenseClaim->id,
                'user_id' => Auth::user()->id,
                'approved' => 1
            ];

            $this->approveClaim($data);
        }

        $users = User::whereIs('admin')->get();
        Notification::send($users, new ExpenseClaimRequested($expenseClaim));

        $user = $expenseClaim->user;
        $user->notify(new ExpenseClaimRequestedTelegram($expenseClaim));

        return $expenseClaim;
    }

    /**
     * Approve the expense claim
     * 
     * @return App\ExpenseClaimApproved
     */
    public function approveClaim(Array $data)
    {
        // Check if claim already approved by this user
        $approvedCheck = ExpenseClaimsApproved::where('expense_claim_id', $data['expense_claim_id'])
            ->where('user_id', $data['user_id'])->first();

        // Check if claim already rejected by other users
        $rejectedCheck = $approvedCheck = ExpenseClaimsApproved::where('expense_claim_id', $data['expense_claim_id'])
        ->where('approved', 0)->first();

        if($approvedCheck || $rejectedCheck) {
            return null;
        }

        $expenseClaimApproved = ExpenseClaimsApproved::create($data);

        $expenseClaim = ExpenseClaim::find($data['expense_claim_id']);
        if($expenseClaim->expenseClaimsApproved()->count() > 1) {
            $user = $expenseClaim->user;
            $user->notify(new ExpenseClaimApproved($expenseClaim, 'user'));
        } else {
            $users = User::whereIs('admin')->get();
            Notification::send($users, new ExpenseClaimApproved($expenseClaim, 'admin'));
        }

        return $expenseClaimApproved;
    }

    /**
     * Reject the expense claim
     * 
     * @return App\ExpenseClaimApproved
     */
    public function rejectClaim(Array $data)
    {
        // Check if claim already approved by this user
        $approvedCheck = ExpenseClaimsApproved::where('expense_claim_id', $data['expense_claim_id'])
            ->where('user_id', $data['user_id'])->first();

        // Check if claim already rejected by other users
        $rejectedCheck = $approvedCheck = ExpenseClaimsApproved::where('expense_claim_id', $data['expense_claim_id'])
        ->where('approved', 0)->first();

        if($approvedCheck || $rejectedCheck) {
            return null;
        }

        $expenseClaimRejected = ExpenseClaimsApproved::create($data);

        $expenseClaim = ExpenseClaim::find($data['expense_claim_id']);

        $user = $expenseClaim->user;
        $user->notify(new ExpenseClaimRejected($expenseClaim, 'user'));
        $users = User::whereIs('admin')->get();
        Notification::send($users, new ExpenseClaimRejected($expenseClaim, 'admin'));

        return $expenseClaimRejected;
    }
}