<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CurrencyService;
use App\Services\ExpenseTypeService;
use App\Services\ExpenseClaimService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        $expenseClaims = $expenseClaimService->getExpenseClaimsByUserId($userId);

        return view('expense-claims.index', compact('expenseClaims'));
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
            'expenses.*.remarks' => 'nullable',
            'expenses.*.file.*' => 'image|mimes:jpeg,png,bmp,jpg,gif,svg,pdf|max:20000'
        ]);

        $periodDate = explode(' - ', $request->period_date);
        $startDate = Carbon::createFromFormat('d/m/Y', $periodDate[0])->startOfDay()->format('Y-m-d H:i:s');
        $endDate = Carbon::createFromFormat('d/m/Y', $periodDate[1])->startOfDay()->format('Y-m-d H:i:s');

        for($i=0; $i < count($request->expenses); $i++) {
            if($request->expenses[$i]['type']) {
                $request->validate([
                    'expenses.'.$i.'.*' => 'required',
                    'expenses.'.$i.'.remarks' => 'nullable',
                ]);
            }
        }
        
        $data = [
            'code' => strtoupper(uniqid('EC')),
            'user_id' => Auth::user()->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'cash_advance' => $request->cash_advance,
            'expenses' => $request->expenses
        ];

        $expenseClaimService = new ExpenseClaimService();
        $expenseClaim = $expenseClaimService->createExpenseClaim($data);

        return $expenseClaim;
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
}
