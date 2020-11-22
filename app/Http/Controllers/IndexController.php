<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Proposal;

use Carbon\Carbon;

class IndexController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     *
     */
    public function signProposal(Request $request, $encodedId)
    {
        $encodedId = \base64_decode($encodedId);
        $proposal_id = (int)explode('-', $encodedId)[0];

        if( !$proposal_id )
            abort(404);
        $proposal = Proposal::find($proposal_id);

        if( is_null($proposal) )
            abort(404);

        if( $proposal->status != 'not-signed' )
            abort(404);

        $data = $proposal->request;

        $data['requestType'] = 'sign';
        $data['proposalId'] = $proposal_id;

        $data['logoImagePath'] = asset("/assets/images/cms-max-rectangle-white.jpg");

        return view('manage-proposal.preview', $data);
    }

    /**
     * Client Signed Proposal
     */
    public function clientSignedProposal(Request $request)
    {
        $data = $request->all();
        $proposal_id = $request->input('proposalId');
        if( !$proposal_id )
            abort(404);

        $proposal = Proposal::find($proposal_id);
        if( is_null($proposal) )
            abort(404);

        if( $proposal->status != 'not-signed' )
            abort(404);

        $proposal->signature    = $request->input('signature');
        $proposal->full_name    = $request->input('fullName');
        $proposal->job_title    = $request->input('jobTitle');
        $proposal->status       = 'signed';
        $proposal->signed_at    = Carbon::now();
        $proposal->save();

        \Mail::send('manage-proposal.mail', [
            'toAdmin'       => true,
            'clientName'    => $proposal->request['clientName']
        ], function($message){
            $message->to(env("ADMIN_EMAIL_ADDRESS", "info@cmsmax.com"), 'Evolution Marketing - CRM')->subject
                ('Evolution Marketing - CRM');
        });

        return view('manage-proposal.successful', ['type' => 'clientSinged']);
    }
}
