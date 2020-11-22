<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Dns;

use Auth;
use Session;

class DnsController extends Controller
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
        if( !Auth::user()->hasPagePermission('Manage DNS') )
            return redirect('/webadmin');

        $dnss = Dns::orderBy('name')->get();
        $data = [
            'currentSection'    => 'manage-dns',
            'dnss'          => $dnss,
        ];
        return view('manage-dns.index', $data);
    }

    public function getDns(Request $request)
    {
        $dns = Dns::find($request->input('dnsId'));

        if( is_null($dns) ){
            return response()->json([
                'status'    => 'error'
            ]);
        }

        return response()->json([
            'status'    => 'success',
            'dns'   => $dns->toArray()
        ]);
    }

    public function addEditDns(Request $request)
    {
        $dnsId = $request->input('dnsId');
        $data = [
            'name'          => $request->input('name'),
            'description'   => $request->input('description')
        ];

        if( $dnsId == "-1" ){   //if add mode
            Session::flash('message', 'Dns added successfully!');
            Session::flash('alert-class', 'alert-success');
            Dns::create($data);
        }
        else {
            $dns = Dns::find($dnsId);
            if( is_null($dns) ){
                return response()->json([
                    'status'    => 'error'
                ]);
            }
            $dns->fill($data);
            $dns->save();

            Session::flash('message', 'Dns edited successfully!');
            Session::flash('alert-class', 'alert-success');
        }
        return response()->json([
            'status'    => 'success',
        ]);
    }

    public function deleteDns(Request $request)
    {
        $dns = Dns::find($request->input('dnsId'));
        if( is_null($dns) ){
            return response()->json([
                'status'    => 'error'
            ]);
        }
        $dns->delete();
        Session::flash('message', 'Dns deleted successfully!');
        Session::flash('alert-class', 'alert-success');

        return response()->json([
            'status'    => 'success',
        ]);
    }
}
