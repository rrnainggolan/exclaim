<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ExpenseType;
use App\Services\ExpenseTypeService;

class ExpenseTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(ExpenseType::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expenseTypeService = new ExpenseTypeService();
        $expenseTypes = $expenseTypeService->getExpenseTypes();

        return view('expense-types.index', compact('expenseTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('expense-types.create');
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
            'name' => 'required'
        ]);

        $expenseTypeService = new ExpenseTypeService();
        $expenseType = $expenseTypeService->createExpenseType($request->all());

        return redirect()->route('expense-types.index')
            ->with('flash_message', 'Expense Type with name: '. $expenseType->name .' added')
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ExpenseType $expenseType)
    {
        return view('expense-types.edit', compact('expenseType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpenseType $expenseType)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $expenseTypeService = new ExpenseTypeService();
        $expenseType = $expenseTypeService->updateExpenseType($expenseType, $request->all());

        return redirect()->route('expense-types.index')
            ->with('flash_message', 'Expense Type with name: '. $expenseType->name .' updated')
            ->with('class', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpenseType $expenseType)
    {
        $expenseTypeService = new ExpenseTypeService();
        $expenseTypeName = $expenseType->name;
        $expenseTypeService->deleteExpenseType($expenseType);
        
        return response()->json([
            'name' => $expenseTypeName,
            'success' => true
        ]);
    }
}
