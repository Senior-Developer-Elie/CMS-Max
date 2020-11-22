<?php

namespace App\Http\Controllers;

use App\Proposal;
use App\Service;

use Auth;

use Illuminate\Http\Request;

use Carbon\Carbon;
use Session;

class ProposalController extends Controller
{
    const tmp_path = 'pdf_tmp/';
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
     * Proposal List
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        //Check page permission and redirect
        if( !Auth::user()->hasPagePermission('Proposals List') )
            return redirect('/webadmin');

        $type = $request->input('type') ? $request->input('type') : 'not-signed';

        $query = Proposal::where('id', '>', 0);
        if( $type == 'not-signed' )
            $query = $query->where('status', 'not-signed');
        else if( $type == 'signed' )
            $query = $query->where('status', "!=", 'not-signed');
        $proposals = $query->get()->toArray();

        $data = [
            'currentSection'    => 'proposal-list',
            'type'              => $type
        ];
        $data['proposals'] = $proposals;
        return view('manage-proposal.proposal-list', $data);
    }

    public function addProposal(Request $request)
    {
        //Check page permission and redirect
        if( !Auth::user()->hasPagePermission('Proposals List') )
            return redirect('/webadmin');

        return view('manage-proposal.add-proposal', [
            'currentSection'    => 'proposal-list',
            'services'          => Service::getServices(),
            'bottomDescription' => Service::getBottomDescription(),
        ]);
    }

    public function process(Request $request)
    {
        $data = $this->getAvailableServices($request->all());
        $data = array_merge($request->all(), $data);

        if( $request->get('requestType') == 'preview' ) //Preview Request
        {
            return view('manage-proposal.preview', $data);
        }
        else if( $request->get('requestType') == 'normal-download' )
        {
            return self::outputPdfFile($data);
        }
        else if( $request->get('requestType') == 'confirm' )
        {
            //Add Proposal To the database
            $proposal = new Proposal([
                'request'   => $data,
                'status'    => 'not-signed'
            ]);
            $proposal->save();

            //Send email if emailContact is checked
            if( isset($data['emailContact']) && $data['emailContact'] == 'on' ){
                //Send Email With Attachment
                \Mail::send('manage-proposal.mail', ['url' => 'sign-proposal/' . ( \base64_encode($proposal->id . '-' . $data['clientName']) )], function($message) use ($data){
                    $message->to($data['clientEmail'], 'CMS Max')->subject
                        ('Website Proposal for ' . $data['clientName']);
                });
            }

            Session::flash('message', 'Proposal added successfully!');
            Session::flash('alert-class', 'alert-success');
            return redirect('/proposal-list');
        }
    }

    /**
     * Edit proposal
     */
    public function editProposal(Request $request)
    {
        //Check page permission and redirect
        if( !Auth::user()->hasPagePermission('Proposals List') )
            return redirect('/webadmin');

        $proposal_id = $request->input('proposalId');

        if( !$proposal_id )
            abort(404);
        $proposal = Proposal::find($proposal_id);

        if( is_null($proposal) )
            abort(404);

        if( $request->isMethod('get') ){
            $data = [
                'currentSection'    => 'proposal-list',
                'proposalId'        => $proposal->id,
                'editMode'          => true,
                'services'          => Service::getServices(),
                'bottomDescription' => Service::getBottomDescription(),
                'request'           => $proposal->request
            ];
            return view("manage-proposal.add-proposal", $data);
        }
        else {
            //Add Proposal To the database
            $data = $this->getAvailableServices($request->all());
            $data = array_merge($request->all(), $data);
            $proposal->request = $data;
            $proposal->status = 'not-signed';
            $proposal->save();

            Session::flash('message', 'Proposal is updated successfully!');
            Session::flash('alert-class', 'alert-success');
            return redirect('/proposal-list');
        }
    }

    /**
     * Admin Download
     */
    public function download(Request $request)
    {
        $proposal_id = $request->input('proposalId');

        if( !$proposal_id )
            abort(404);
        $proposal = Proposal::find($proposal_id);

        if( is_null($proposal) )
            abort(404);

        $data = $proposal->request;
        $data['requestType']    = 'admin-download';
        $data['status']         = $proposal->status;
        $data['fullName']       = $proposal->full_name;
        $data['jobTitle']       = $proposal->job_title;
        $data['signature']      = $proposal->signature;

        return self::outputPdfFile($data);
    }

    /**
     * Delete Proposal
     */
    public function deleteProposal(Request $request)
    {
        $proposal_id = $request->input('proposalId');

        if( !$proposal_id )
            abort(404);
        $proposal = Proposal::find($proposal_id);

        if( is_null($proposal) )
            abort(404);

        if( $request->isMethod('get') )
        {
            $data = [
                'currentSection'    => 'proposal-list',
                'proposal'          => $proposal
            ];
            return view("manage-proposal.delete-proposal", $data);
        }
        else
        {
            $proposal->delete();

            Session::flash('message', 'Proposal is removed successfully!');
            Session::flash('alert-class', 'alert-success');
            return redirect('/proposal-list');
        }
    }

    /**
     * Manually Sign Proposal
     */
    public function manualSign(Request $request)
    {
        $proposal_id = $request->input('proposalId');

        if( !$proposal_id )
            abort(404);
        $proposal = Proposal::find($proposal_id);

        if( is_null($proposal) )
            abort(404);

        if( $request->isMethod('get') )
        {
            $data = [
                'currentSection'    => 'proposal-list',
                'proposal'          => $proposal
            ];
            return view("manage-proposal.manual-sign-proposal", $data);
        }
        else
        {
            $proposal->status       = 'manual-signed';
            $proposal->signed_at    = Carbon::now();
            $proposal->save();

            Session::flash('message', 'Proposal is signed manually!');
            Session::flash('alert-class', 'alert-success');
            return redirect('/proposal-list');
        }
    }

    /**
     * Send proposal email again
     */
    public function sendEmail(Request $request)
    {
        $proposal_id = $request->input('proposalId');

        if( !$proposal_id )
            abort(404);
        $proposal = Proposal::find($proposal_id);

        if( is_null($proposal) )
            abort(404);

        if( $request->isMethod('get') )
        {
            $data = [
                'currentSection'    => 'proposal-list',
                'proposal'          => $proposal
            ];
            return view("manage-proposal.send-email", $data);
        }
        else {
            $changedRequest = $proposal->request;
            $changedRequest['addSignature'] = 'on';
            $changedRequest['emailContact'] = 'on';
            $proposal->request = $changedRequest;
            $proposal->save();
            //Send Email With Attachment
            \Mail::send('manage-proposal.mail', ['url' => 'sign-proposal/' . ( \base64_encode($request->get('proposalId') . '-' . $proposal->request['clientName']) ) ], function($message) use ($request, $proposal){
                $message->to($request->get('clientEmail'), 'CMS Max')->subject
                    ('Website Proposal for ' . $proposal->request['clientName'] );
            });

            Session::flash('message', 'Your email was sent to ' . $request->get('clientEmail') . ' successfully.');
            Session::flash('alert-class', 'alert-success');

            return redirect('/proposal-list');
        }
    }

    /**
     * Change Proposal Sold Status
     */
    public function changeProposalSoldStatus(Request $request, $proposalId, bool $sold)
    {
        $proposal = Proposal::find($proposalId);
        if( is_null($proposal) ) {
            abort(404);
        }

        if( $request->isMethod('get') ) {
            $data = [
                'currentSection'    => 'proposal-list',
                'proposal'          => $proposal
            ];
            return view("manage-proposal.change-proposal-sold", $data);
        }
        else if( $request->isMethod('post') ) {
            $proposal->sold = $sold;
            $proposal->save();

            Session::flash('message', 'Proposal status changed successfully.');
            Session::flash('alert-class', 'alert-success');

            return redirect('/proposal-list');
        }

    }

    protected function getAvailableServices($data)
    {
        $one_time_services  = Service::getServices('one-time');
        $recurring_services = Service::getServices('recurring');

        $availableOneTimeServices = [];
        $availableRecurringSerices = [];
        foreach( $one_time_services as $service )
        {
            if( isset($data[$service['name']]) && $data[$service['name']] == 'on' )
            {
                $prettyService = $service;
                $prettyService['price'] = ( isset($data[$service['name'] . '-price']) && !is_null($data[$service['name'] . '-price']) ) ? $data[$service['name'] . '-price'] : 0;
                $prettyService['content'] = ( isset($data[$service['name'] . '-content']) && !is_null($data[$service['name'] . '-content']) ) ? $data[$service['name'] . '-content'] : 0;
                $availableOneTimeServices[] = $prettyService;
            }

        }
        $totalRecurringFee = 0;
        foreach( $recurring_services as $service )
        {
            if( isset($data[$service['name']]) && $data[$service['name']] == 'on' )
            {
                $prettyService = $service;
                $prettyService['price'] = ( isset($data[$service['name'] . '-price']) && !is_null($data[$service['name'] . '-price']) ) ? $data[$service['name'] . '-price'] : 0;
                $prettyService['content'] = ( isset($data[$service['name'] . '-content']) && !is_null($data[$service['name'] . '-content']) ) ? $data[$service['name'] . '-content'] : 0;
                $availableRecurringSerices[] = $prettyService;

                $totalRecurringFee += (int)$prettyService['price'];
            }
        }
        return array_merge([
            'oneTimeServices'   => $availableOneTimeServices,
            'recurringServices' => $availableRecurringSerices,
            'totalRecurringFee' => $totalRecurringFee
        ], $data);
    }

    /**
     * Download PDF File
     */
    public static function outputPdfFile($data)
    {
        $pdf = \PDF::loadView('manage-proposal.preview', $data);

        $file_name = uniqid();
        /*
        if ( !file_exists(self::tmp_path) )
        {
            mkdir(self::tmp_path, 0700);
        }*/
        //return $pdf->stream();
        //$pdf->setWarnings(false)->save($file_name . '.pdf');

        return $pdf->download(($data['clientName'] ? $data['clientName'] : 'document') . ".pdf")->withHeaders([
            'X-Vapor-Base64-Encode' => 'True',
        ]);

        //Get Full Path of Generated PDF File
        /*$file_full_path = $file_name . '.pdf';
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=\"" . ( $data['clientName'] ? $data['clientName'] : 'document') . ".pdf\"");
        header("Content-Transfer-Encoding: binary ");
        readfile($file_full_path);
        unlink($file_full_path);*/
    }
}
