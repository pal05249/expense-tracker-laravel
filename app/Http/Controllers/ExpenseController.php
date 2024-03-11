<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpensePostRequest;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
       $expenses= Expense::getPaginatedData(request());
        return response()->json(['expenses' => $expenses['paginatedData'],'totalAmount'=>$expenses['totalAmount']], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\ExpensePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpensePostRequest $request)
    {
        //
        $expenses = new Expense();
        $expenses->insert([
            'category_id' => $request->categoryId,
            'amount' => $request->amount,
            'description' => $request->description,
            'date_of_expense' => $request->dateOfExpense,
            'user_id' => Auth::id()
        ]);
        return response()->json(['message' => 'Successfully added'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expenses)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\ExpensePostRequest  $request
     * @param  integer $expense
     * @return \Illuminate\Http\Response
     */
    public function update(ExpensePostRequest $request, $expenseId)
    {
        
        Expense::where('id', $expenseId)->update(['amount' => $request->amount, 'description' => $request->description, 'category_id' => $request->categoryId, 'date_of_expense' => $request->dateOfExpense]);

        return response()->json(['message' => 'Updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $expenseId
     * @return \Illuminate\Http\Response
     */
    public function destroy($expenseId)
    {
        //
        $expenseModel = Expense::find($expenseId);
        $expenseModel->delete();
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
