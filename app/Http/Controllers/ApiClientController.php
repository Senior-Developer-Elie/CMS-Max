<?php

namespace App\Http\Controllers;

use App\ArchivedApiClient;
use App\Client;
use Illuminate\Http\Request;

use App\Http\Helpers\AngelInvoiceHelper;

use Session;
class ApiClientController extends Controller
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

    public function index(Request $request)
    {
        $apiClients = AngelInvoiceHelper::getClients(true);
        $clients = Client::get();

        $apiClientIds = array_column($apiClients, 'id');
        $clientIds = array_column($clients->toArray(), 'api_id');
        $archivedApiClientIds = array_column(ArchivedApiClient::get()->toArray(), 'api_client_id');
        $realArchivedApiClientIds = [];
        foreach ($archivedApiClientIds as $apiClientId) {
            if (in_array($apiClientId, $apiClientIds)) {
                $realArchivedApiClientIds[] = $apiClientId;
            }
        }

        $pendingApiClientIds = [];
        foreach( $apiClientIds as $apiId ){
            if(  !in_array($apiId, $clientIds) && !in_array($apiId, $realArchivedApiClientIds) )
                $pendingApiClientIds[] = $apiId;
        }

        return view('manage-client.api-client-list', [
            'currentSection'        => 'api-client-list',
            'apiClients'            => $apiClients,
            'pendingApiClientIds'   => $pendingApiClientIds,
            'archivedApiClientIds'  => $realArchivedApiClientIds
        ]);
    }

    public function archiveApiClient(Request $request)
    {
        $apiClientId = $request->input('apiClientId');

        if( count(ArchivedApiClient::where('api_client_id', $apiClientId)->get()) ==0 ){
            ArchivedApiClient::create([
                'api_client_id' => $apiClientId
            ]);
        }

        Session::flash('message', 'Api client archived!');
        Session::flash('alert-class', 'alert-success');

        return response()->json([
            'status'    => 'success'
        ]);
    }

    public function unarchiveApiClient(Request $request)
    {
        $apiClientId = $request->input('apiClientId');

        $apiClient = ArchivedApiClient::where('api_client_id', $apiClientId)->first();

        if( !is_null($apiClient) )
            $apiClient->delete();

        Session::flash('message', 'Api client enabled!');
        Session::flash('alert-class', 'alert-success');

        return response()->json([
            'status'    => 'success'
        ]);
    }
}
