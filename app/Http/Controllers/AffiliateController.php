<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Affiliate;

use Auth;
use Session;

class AffiliateController extends Controller
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
        if( !Auth::user()->hasPagePermission('Manage Affiliates') )
            return redirect('/webadmin');

        $affiliates = Affiliate::orderBy('name')->get();
        $data = [
            'currentSection'    => 'manage-affiliate',
            'affiliates'          => $affiliates,
        ];
        return view('manage-affiliate.index', $data);
    }

    public function getAffiliate(Request $request)
    {
        $affiliate = Affiliate::find($request->input('affiliateId'));

        if( is_null($affiliate) ){
            return response()->json([
                'status'    => 'error'
            ]);
        }

        return response()->json([
            'status'    => 'success',
            'affiliate'   => $affiliate->toArray()
        ]);
    }

    public function addEditAffiliate(Request $request)
    {
        $affiliateId = $request->input('affiliateId');
        $data = [
            'name'          => $request->input('name'),
            'description'   => $request->input('description')
        ];

        if( $affiliateId == "-1" ){   //if add mode
            Session::flash('message', 'Affiliate added successfully!');
            Session::flash('alert-class', 'alert-success');
            Affiliate::create($data);
        }
        else {
            $affiliate = Affiliate::find($affiliateId);
            if( is_null($affiliate) ){
                return response()->json([
                    'status'    => 'error'
                ]);
            }
            $affiliate->fill($data);
            $affiliate->save();

            Session::flash('message', 'Affiliate edited successfully!');
            Session::flash('alert-class', 'alert-success');
        }
        return response()->json([
            'status'    => 'success',
        ]);
    }

    public function deleteAffiliate(Request $request)
    {
        $affiliate = Affiliate::find($request->input('affiliateId'));
        if( is_null($affiliate) ){
            return response()->json([
                'status'    => 'error'
            ]);
        }
        $affiliate->delete();
        Session::flash('message', 'Affiliate deleted successfully!');
        Session::flash('alert-class', 'alert-success');

        return response()->json([
            'status'    => 'success',
        ]);
    }
}
