<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Profit;

use Auth;
use Session;

class ProfitController extends Controller
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
        if( !Auth::user()->hasPagePermission('Manage P&L Profits') )
            return redirect('/webadmin');

        $profits = Profit::orderBy('name')->get();
        $data = [
            'currentSection'    => 'manage-profit',
            'profits'          => $profits,
        ];
        return view('manage-profit.index', $data);
    }

    public function getProfit(Request $request)
    {
        $profit = Profit::find($request->input('profitId'));

        if( is_null($profit) ){
            return response()->json([
                'status'    => 'error'
            ]);
        }

        return response()->json([
            'status'    => 'success',
            'profit'   => $profit->toArray()
        ]);
    }

    public function addEditProfit(Request $request)
    {
        $profitId = $request->input('profitId');
        $data = [
            'name'          => $request->input('name'),
            'price'         => floatval($request->input('price')),
            'description'   => $request->input('description')
        ];

        if( $profitId == "-1" ){   //if add mode
            Session::flash('message', 'Profit added successfully!');
            Session::flash('alert-class', 'alert-success');
            Profit::create($data);
        }
        else {
            $profit = Profit::find($profitId);
            if( is_null($profit) ){
                return response()->json([
                    'status'    => 'error'
                ]);
            }
            $profit->fill($data);
            $profit->save();

            Session::flash('message', 'Profit edited successfully!');
            Session::flash('alert-class', 'alert-success');
        }
        return response()->json([
            'status'    => 'success',
        ]);
    }

    public function deleteProfit(Request $request)
    {
        $profit = Profit::find($request->input('profitId'));
        if( is_null($profit) ){
            return response()->json([
                'status'    => 'error'
            ]);
        }
        $profit->delete();
        Session::flash('message', 'Profit deleted successfully!');
        Session::flash('alert-class', 'alert-success');

        return response()->json([
            'status'    => 'success',
        ]);
    }
}
