<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CurrencyService;
use App\Services\ExpenseTypeService;
use App\Services\ExpenseClaimService;
use App\Services\ExpenseService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Bouncer;

class ExpenseClaimController extends Controller
{
    /**
     * Controller constructors.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::id();
        $expenseClaimService = new ExpenseClaimService();

        $myPendingExpenseClaims = $expenseClaimService->getMyPendingExpenseClaims();
        $myCompletedExpenseClaims = $expenseClaimService->getMyCompletedExpenseClaims();

        return view('expense-claims.index', compact('myPendingExpenseClaims', 'myCompletedExpenseClaims'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currencyService = new CurrencyService();
        $currencies = $currencyService->getCurrencies()->pluck('name', 'id');

        $expenseTypeService = new ExpenseTypeService();
        $expenseTypes = $expenseTypeService->getExpenseTypes()->pluck('name', 'id');

        return view('expense-claims.create', compact('currencies', 'expenseTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'period_date' => 'required',
            'expenses.0.type' => 'required',
            'expenses.*.type' => 'required_with:expenses.*.amount',
            'expenses.*.amount' => 'required_with:expenses.*.type',
            'expenses.*.remarks' => 'nullable',
            'expenses.*.file.*' => 'mimes:jpeg,png,bmp,jpg,gif,svg,pdf|max:20000'
        ]);

        $periodDate = explode(' - ', $request->period_date);
        $startDate = Carbon::createFromFormat('d/m/Y', $periodDate[0])->startOfDay()->format('Y-m-d H:i:s');
        $endDate = Carbon::createFromFormat('d/m/Y', $periodDate[1])->startOfDay()->format('Y-m-d H:i:s');
        
        $data = [
            'code' => strtoupper(uniqid('EC')),
            'user_id' => Auth::user()->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'description' => $request->description,
        ];

        if($request->cash_advance) {
            $data['cash_advance'] = $request->cash_advance;
        }

        $expenseClaimService = new ExpenseClaimService();
        $expenseClaim = $expenseClaimService->createExpenseClaim($data);

        $expenseService = new ExpenseService();
        $expenseService->createExpenses($expenseClaim->id, $request->expenses);

        return redirect()->route('expense-claims.index')
            ->with('flash_message', 'Claim with code: '. $expenseClaim->code .' added')
            ->with('class', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $expenseClaimService = new ExpenseClaimService();
        $expenseClaim = $expenseClaimService->getExpenseClaim($id);

        return view('expense-claims.show', compact('expenseClaim'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Display all active expenses claims
     * 
     * @return \Illuminate\Http\Response
     */
    public function active()
    {
        $this->authorize('approve-claims');

        $expenseClaimService = new ExpenseClaimService();
        $activeExpenseClaims = $expenseClaimService->getActiveExpenseClaims();
        $data = compact('activeExpenseClaims');

        return view('expense-claims.active', $data);
    }

    /**
     * Display all completed expenses claims
     * 
     * @return \Illuminate\Http\Response
     */
    public function completed()
    {
        $this->authorize('approve-claims');

        $expenseClaimService = new ExpenseClaimService();
        $completedExpenseClaims = $expenseClaimService->getCompletedExpenseClaims();

        return view('expense-claims.completed', compact('completedExpenseClaims'));
    }

    /**
     * Approve the claim request
     * 
     * @return App\ExpenseClaimApproved
     */
    public function approve(Request $request)
    {
        $this->authorize('approve-claims');

        $data = [
            'expense_claim_id' => $request->id,
            'user_id' => $request->user_id,
            'approved' => 1
        ];

        $expenseClaimService = new ExpenseClaimService();
        $approveClaim = $expenseClaimService->approveClaim($data);

        if(!$approveClaim) {
            return redirect()->route('expense-claims.show', ['id' => $approveClaim->expense_claim_id])
            ->with('flash_message', 'Error, claim already approved')
            ->with('class', 'alert');
        }

        return redirect()->route('expense-claims.completed')
            ->with('flash_message', 'Claim successfully approved!')
            ->with('class', 'success');
    }

    /**
     * Reject the claim request
     * 
     * @return App\ExpenseClaimApproved
     */
    public function reject(Request $request)
    {
        $this->authorize('approve-claims');

        $data = [
            'expense_claim_id' => $request->id,
            'user_id' => $request->user_id,
            'reason' => $request->reason,
            'approved' => 0
        ];

        $expenseClaimService = new ExpenseClaimService();
        $rejectClaim = $expenseClaimService->rejectClaim($data);

        if(!$rejectClaim) {
            return redirect()->route('expense-claims.show', ['id' => $rejectClaim->expense_claim_id])
            ->with('flash_message', 'Error, claim already rejected')
            ->with('class', 'alert');
        }

        return redirect()->route('expense-claims.completed')
            ->with('flash_message', 'Claim successfully rejected!')
            ->with('class', 'success');
    }
}
