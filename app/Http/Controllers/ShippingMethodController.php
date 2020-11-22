<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\ShippingMethod;

use Auth;
use Session;

class ShippingMethodController extends Controller
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
        if( !Auth::user()->hasPagePermission('Manage Shipping Methods') )
            return redirect('/webadmin');

        $shippingMethods = ShippingMethod::orderBy('name')->get();
        $data = [
            'currentSection'    => 'manage-shippingMethod',
            'shippingMethods'          => $shippingMethods,
        ];
        return view('manage-shippingMethod.index', $data);
    }

    public function getShippingMethod(Request $request)
    {
        $shippingMethod = ShippingMethod::find($request->input('shippingMethodId'));

        if( is_null($shippingMethod) ){
            return response()->json([
                'status'    => 'error'
            ]);
        }

        return response()->json([
            'status'    => 'success',
            'shippingMethod'   => $shippingMethod->toArray()
        ]);
    }

    public function addEditShippingMethod(Request $request)
    {
        $shippingMethodId = $request->input('shippingMethodId');
        $data = [
            'name'          => $request->input('name'),
            'description'   => $request->input('description')
        ];

        if( $shippingMethodId == "-1" ){   //if add mode
            Session::flash('message', 'ShippingMethod added successfully!');
            Session::flash('alert-class', 'alert-success');
            ShippingMethod::create($data);
        }
        else {
            $shippingMethod = ShippingMethod::find($shippingMethodId);
            if( is_null($shippingMethod) ){
                return response()->json([
                    'status'    => 'error'
                ]);
            }
            $shippingMethod->fill($data);
            $shippingMethod->save();

            Session::flash('message', 'ShippingMethod edited successfully!');
            Session::flash('alert-class', 'alert-success');
        }
        return response()->json([
            'status'    => 'success',
        ]);
    }

    public function deleteShippingMethod(Request $request)
    {
        $shippingMethod = ShippingMethod::find($request->input('shippingMethodId'));
        if( is_null($shippingMethod) ){
            return response()->json([
                'status'    => 'error'
            ]);
        }
        $shippingMethod->delete();
        Session::flash('message', 'ShippingMethod deleted successfully!');
        Session::flash('alert-class', 'alert-success');

        return response()->json([
            'status'    => 'success',
        ]);
    }
}
