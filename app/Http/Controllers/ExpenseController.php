<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Expense;

use Auth;
use Session;

class ExpenseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //Check page permission and redirect
        if( !Auth::user()->hasPagePermission('Manage P&L Expenses') )
            return redirect('/webadmin');

        $expenses = Expense::orderBy('name')->get();
        $data = [
            'currentSection'    => 'manage-expense',
            'expenses'          => $expenses,
        ];
        return view('manage-expense.index', $data);
    }

    public function getExpense(Request $request)
    {
        $expense = Expense::find($request->input('expenseId'));

        if( is_null($expense) ){
            return response()->json([
                'status'    => 'error'
            ]);
        }

        return response()->json([
            'status'    => 'success',
            'expense'   => $expense->toArray()
        ]);
    }

    public function addEditExpense(Request $request)
    {
        $expenseId = $request->input('expenseId');
        $data = [
            'name'          => $request->input('name'),
            'price'         => floatval($request->input('price')),
            'description'   => $request->input('description')
        ];

        if( $expenseId == "-1" ){   //if add mode
            Session::flash('message', 'Expense added successfully!');
            Session::flash('alert-class', 'alert-success');
            Expense::create($data);
        }
        else {
            $expense = Expense::find($expenseId);
            if( is_null($expense) ){
                return response()->json([
                    'status'    => 'error'
                ]);
            }
            $expense->fill($data);
            $expense->save();

            Session::flash('message', 'Expense edited successfully!');
            Session::flash('alert-class', 'alert-success');
        }
        return response()->json([
            'status'    => 'success',
        ]);
    }

    public function deleteExpense(Request $request)
    {
        $expense = Expense::find($request->input('expenseId'));
        if( is_null($expense) ){
            return response()->json([
                'status'    => 'error'
            ]);
        }
        $expense->delete();
        Session::flash('message', 'Expense deleted successfully!');
        Session::flash('alert-class', 'alert-success');

        return response()->json([
            'status'    => 'success',
        ]);
    }
}
