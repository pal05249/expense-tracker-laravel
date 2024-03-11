<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Expense extends Model
{
    use HasFactory;

    /**
     * Get the category associated with the expense.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    /**
     * Fetch paginated list of expenses
     *
     * @param Request $request 
     * @return Collection $expenses;
     **/
    public static function getPaginatedData($request)
    {
        //check if query params is null or not ,using nullish coalescing operator(??)
        $searchValue = $request['q'] ?? '';
        $paginate = $request['paginate'] ?? 5;
        $sortDirection = $request['sortDirection'] ?? 'desc';
        $sortField = $request['sortField'] ?? 'id';
        $categoryId = $request['categoryId'] ?? '';
        $fromDate = $request['fromDate'] ?? '';
        $toDate = $request['toDate'] ?? '';

        $expensesQuery = Expense::with(['category' => function ($query) {
            $query->select('id', 'category_name');
        }])->where('user_id', Auth::id())
            //filter by search
            ->when($searchValue, function ($query) use ($searchValue) {
                $query->where('amount', 'like', '%' . $searchValue . '%')
                    ->orWhere('date_of_expense', 'like', '%' . $searchValue . '%')
                    ->orWhereHas('category', function ($q) use ($searchValue) {
                        $q->where('category_name', 'like', '%' . $searchValue . '%');
                    })
                    ->orWhere('description', 'like', '%' . $searchValue . '%');
            })
            //filter by category
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            //filter by dates
            ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('date_of_expense', [$fromDate, $toDate]);
            });
        $totalAmount = $expensesQuery->sum('amount');
        $expenses = $expensesQuery->orderBy($sortField, $sortDirection)->paginate($paginate);
        return ['paginatedData' => $expenses, 'totalAmount' => $totalAmount];
    }

    public function generateDougnutChartData($request)
    {
        $year = $request['year'];
        $month = $request['month'];
        $startDate = (new DateTime("$year-$month-01"))->format('Y-m-d'); // Start date
        $endDate =  (new DateTime("$year-$month-01"))->modify('last day of')->format('Y-m-d');  // End date

        $expensesSummary = Expense::with('category')
            ->where('user_id', Auth::id())
            ->whereBetween('date_of_expense', [$startDate, $endDate])
            ->groupBy('category_id')
            ->select('category_id', DB::raw('SUM(amount) as total_amount'))
            ->get();

        $labels = [];
        $data = [];
        $totalExpenseAmount = 0;
        for ($i = 0; $i < count($expensesSummary); $i++) {
            array_push($labels, $expensesSummary[$i]->category->category_name);
            array_push($data, $expensesSummary[$i]->total_amount);
            $totalExpenseAmount += $expensesSummary[$i]->total_amount;
        }

        return response()->json(['labels' => $labels, 'data' => $data, 'totalExpenseAmount' => $totalExpenseAmount]);
    }

    public function generateAreaChartData($request)
    {
        $year = $request['year'];
        $expensesSummary = Expense::with('category')
            ->select('categories.category_name as categoryName', DB::raw('YEAR(date_of_expense) as year'), DB::raw('MONTH(date_of_expense) as month'), DB::raw('SUM(amount) as total_amount'))
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->where('expenses.user_id', Auth::id())
            ->whereYear('date_of_expense', $year)
            ->groupBy('categories.category_name', DB::raw('YEAR(date_of_expense)'), DB::raw('MONTH(date_of_expense)'))
            ->get();

        $expenseData = [];

        foreach ($expensesSummary as $expense) {
            $categoryName = $expense->categoryName;
            $month = $expense->month;
            $totalAmount = $expense->total_amount;

            $index = -1;
            foreach ($expenseData as $key => $data) {
                if ($data['label'] == $categoryName) {
                    $index = $key;
                    break;
                }
            }

            if ($index == -1) {
                $expenseData[] = [
                    'label' => $categoryName,
                    'data' => array_fill(0, 12, 0),
                    'fill'=>'origin'
                ];
                $index = count($expenseData) - 1;
            }

            $expenseData[$index]['data'][$month - 1] = $totalAmount;
        }

        $expenseSummary = ['datasets' => $expenseData];

        return response()->json(['expenseSummary' => $expenseSummary]);
    }
}
