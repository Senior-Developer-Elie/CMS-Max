<?php

namespace App\Http\Controllers;

use App\CreditRate;

use Illuminate\Http\Request;
use Session;
use Auth;

class CreditRateController extends Controller
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

    const tmp_path = 'pdf_tmp/';

    public function index(Request $request)
    {
        return view('credit.index', [
            'cardRatings' => CreditRate::getRateDefault(),
            'currentSection' => 'calculate-card',
            'initialExpandOnHover'      => true
        ]);
    }

    public function generatePDF(Request $request)
    {
        /*return view('pdf', [
            'cardRatings'   => json_decode($request->input('creditRatings'), true),
            'projectName'   => $request->input('projectName'),
            'ccRep'         => $request->input('ccRep')
        ]);*/
        $pdf = \PDF::loadView('credit.pdf', [
            'cardRatings'   => json_decode($request->input('creditRatings'), true),
            'projectName'   => $request->input('projectName'),
            'ccRep'         => $request->input('ccRep')
        ]);
        //return $pdf->stream();

        $file_name = uniqid();
        // if ( !file_exists(self::tmp_path) )
        // {
        //     mkdir(self::tmp_path, 0700);
        // }
        //return $pdf->stream();
        //$pdf->setWarnings(false)->save($file_name . '.pdf');
        return $pdf->download(( $request->input('projectName') ? $request->input('projectName') : 'document') . '.pdf')->withHeaders([
            'X-Vapor-Base64-Encode' => 'True',
        ]);
/*
        //Get Full Path of Generated PDF File
        $file_full_path = public_path($file_name . '.pdf');
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=" . ( $request->input('projectName') ? $request->input('projectName') : 'document') . '.pdf' );
        header("Content-Transfer-Encoding: binary ");
        readfile($file_full_path);
        unlink($file_full_path);
        */
    }

    /**
     * Manage Default Text
     */
    public function manageDefaultRate(Request $request)
    {
        //Check page permission and redirect
        if( !Auth::user()->hasPagePermission('Manage Default Card Rate') )
            return redirect('/webadmin');

        if( $request->isMethod('get') )
        {
            $creditRate = CreditRate::getRateDefault();
            return view("credit.edit", [
                'currentSection'    => 'manage-default-rate',
                'cardRatings'       => $creditRate
            ]);
        }
        else
        {
            CreditRate::updateRateDefault(json_decode($request->get('creditRatings'), true));
            Session::flash('message', 'Default Rate is updated successfully!');
            Session::flash('alert-class', 'alert-success');
            return redirect('/manage-default-rate');
        }
    }
}
