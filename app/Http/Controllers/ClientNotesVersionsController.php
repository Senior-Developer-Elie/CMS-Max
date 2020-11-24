<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Client;
use App\Blog;
use App\Website;
use App\BlogIndustry;
use App\User;
use App\InnerBlog;
use App\AdminHistory;
use App\AngelInvoice;
use App\ClientNotesVersion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

use App\Http\Helpers\AngelInvoiceHelper;
use App\Http\Helpers\WebsiteHelper;
class ClientNotesVersionsController extends Controller
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

    public function show(Request $request)
    {
        if (! $clientNotesVersion = ClientNotesVersion::find($request->input('client_notes_version_id'))) {
            abort(404);
        }

        return response()->json([
            'status'    => 'success',
            'notes' => $clientNotesVersion->notes
        ]);
    }
}
