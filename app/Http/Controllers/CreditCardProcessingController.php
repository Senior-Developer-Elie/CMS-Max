<?php

namespace App\Http\Controllers;

use App\CreditCardProcessing;
use App\Http\Helpers\WebsiteHelper;
use Illuminate\Http\Request;

use App\Website;
use App\PaymentGateway;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CreditCardProcessingController extends Controller
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

    /**
     * Payroll Websites List
     */
    public function index(Request $request)
    {
        //Check page permission and redirect
        if( !Auth::user()->hasPagePermission('Credit Card Processing') )
            return redirect('/webadmin');

        $naPaymentGateway = PaymentGateway::where('key', 'n/a')->first();
        $needToSellPaymentGateway = PaymentGateway::where('key', 'need-to-sell')->first();

        $websites = Website::all();
        $tsysWebsites = [];
        $pendingWebsites = [];
        $archivedWebsites = [];

        foreach( $websites as $website ) {
            if ($website->credit_card_archived || in_array($naPaymentGateway->id, $website->payment_gateway)) {
                $archivedWebsites[] = $website;
            } elseif (empty($website->payment_gateway) || in_array($needToSellPaymentGateway->id, $website->payment_gateway)) {
                $pendingWebsites[] = $website;
            } else {
                $tsysWebsites[] = $website;
            }
        }

        return view('manage-website.credit-card-processing-list', [
            'currentSection'   => 'credit-card-processing',
            'websites'         => $tsysWebsites,
            'pendingWebsites'  => $pendingWebsites,
            'archivedWebsites' => $archivedWebsites,
            'allPaymentGateways' => WebsiteHelper::getAllPaymentGateways(),
            'creditCardProcessings' => CreditCardProcessing::all()
        ]);
    }

    /**
     * Archive Website
     */
    public function archiveWebsite(Request $request)
    {
        $website = Website::find($request->input('websiteId'));
        if( is_null($website) )
            return response()->json([
                'status'    => 'error'
            ]);
        $website->credit_card_archived = true;
        $website->save();

        Session::flash('message', 'Website is archived Successfully!');
        Session::flash('alert-class', 'alert-success');

        return response()->json([
            'status'    => 'success'
        ]);
    }

    /**
     * Archive Website
     */
    public function unarchiveWebsite(Request $request)
    {
        $website = Website::find($request->input('websiteId'));
        if( is_null($website) )
            return response()->json([
                'status'    => 'error'
            ]);
        $website->credit_card_archived = false;
        $website->save();

        Session::flash('message', 'Website is Re-enabled Successfully!');
        Session::flash('alert-class', 'alert-success');

        return response()->json([
            'status'    => 'success'
        ]);
    }
    
    /**
     * Api, Add manual credit card entry
     */
    public function store(Request $request)
    {
        $data = $request->all();

        CreditCardProcessing::create([
            'company_name' => $data['company_name'],
            'payment_gateway' => $data['payment_gateway']
        ]);

        Session::flash('message', 'Manual entry added successfully!');
        Session::flash('alert-class', 'alert-success');

        return response()->json([
            'status'    => 'success'
        ]);
    }

    /**
     * Inline Editing Update attribute of website
     */
    public function updateAttribute(Request $request)
    {
        $creditCardProcessingId  = $request->input('pk');
        $key        = $request->input('name');
        $value      = $request->input('value');

        $creditCardProcessing = CreditCardProcessing::find($creditCardProcessingId);
        if( is_null($creditCardProcessing) ){
            return response()->json([
                'status'    => 'error'
            ]);
        }
        $creditCardProcessing->$key = $value;
        $creditCardProcessing->save();

        return response()->json([
            'status'    => 'success'
        ]);
    }

    /**
     * Delete manual entry
     */
    public function destroy(Request $request)
    {
        if (! $creditCardProcessing = CreditCardProcessing::find($request->input('creditCardProcessingId'))) {
            return response()->json([
                'status'    => 'error'
            ]);
        }

        $creditCardProcessing->delete();
        
        Session::flash('message', 'Manual entry deleted successfully!');
        Session::flash('alert-class', 'alert-success');

        return response()->json([
            'status'    => 'success'
        ]);
    }
}
