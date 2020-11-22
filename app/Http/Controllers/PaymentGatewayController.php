<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\PaymentGateway;

use Auth;
use Session;

class PaymentGatewayController extends Controller
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
        if( !Auth::user()->hasPagePermission('Manage Payment Gateways') )
            return redirect('/webadmin');

        $paymentGateways = PaymentGateway::orderBy('name')->get();
        $data = [
            'currentSection'    => 'manage-paymentGateway',
            'paymentGateways'          => $paymentGateways,
        ];
        return view('manage-paymentGateway.index', $data);
    }

    public function getPaymentGateway(Request $request)
    {
        $paymentGateway = PaymentGateway::find($request->input('paymentGatewayId'));

        if( is_null($paymentGateway) ){
            return response()->json([
                'status'    => 'error'
            ]);
        }

        return response()->json([
            'status'    => 'success',
            'paymentGateway'   => $paymentGateway->toArray()
        ]);
    }

    public function addEditPaymentGateway(Request $request)
    {
        $paymentGatewayId = $request->input('paymentGatewayId');
        $data = [
            'name'          => $request->input('name'),
            'description'   => $request->input('description')
        ];

        if( $paymentGatewayId == "-1" ){   //if add mode
            Session::flash('message', 'PaymentGateway added successfully!');
            Session::flash('alert-class', 'alert-success');
            PaymentGateway::create($data);
        }
        else {
            $paymentGateway = PaymentGateway::find($paymentGatewayId);
            if( is_null($paymentGateway) ){
                return response()->json([
                    'status'    => 'error'
                ]);
            }
            $paymentGateway->fill($data);
            $paymentGateway->save();

            Session::flash('message', 'PaymentGateway edited successfully!');
            Session::flash('alert-class', 'alert-success');
        }
        return response()->json([
            'status'    => 'success',
        ]);
    }

    public function deletePaymentGateway(Request $request)
    {
        $paymentGateway = PaymentGateway::find($request->input('paymentGatewayId'));
        if( is_null($paymentGateway) ){
            return response()->json([
                'status'    => 'error'
            ]);
        }
        $paymentGateway->delete();
        Session::flash('message', 'PaymentGateway deleted successfully!');
        Session::flash('alert-class', 'alert-success');

        return response()->json([
            'status'    => 'success',
        ]);
    }
}
