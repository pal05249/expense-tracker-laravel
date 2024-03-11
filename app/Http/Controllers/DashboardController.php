<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request('route-use') == "cardDetails")
            return $this->fetchCardDetails();
    }

    private function fetchCardDetails()
    {
        //total transactions
        $totalTransactionsCount = Expense::where('user_id', Auth::id())->count();
        //total expense amount
        $totalExpenseAmount = Expense::where('user_id', Auth::id())->sum('amount');
        //current month expense amount
        $totalExpenseForCurrentMonth = Expense::where('user_id', Auth::id())->whereMonth('date_of_expense', date('m'))->sum('amount');


        return response()->json(['totalTransactionsCount' => $totalTransactionsCount, 'totalExpenseAmount' => $totalExpenseAmount, 'totalExpenseForCurrentMonth' => $totalExpenseForCurrentMonth], 200);
    }

    public function fetchLoggedInUserDetails()
    {
        return response()->json(['userDetails' => Auth::user()], 200);
    }

    public function fetchDoughnutChartDetails()
    {
        $expenseModel = new Expense();
        return $expenseModel->generateDougnutChartData(request());
    }
    public function fetchAreaChartDetails()
    {
        $expenseModel = new Expense();
        return $expenseModel->generateAreaChartData(request());
    }
}
