<?php

namespace App\Http\Controllers;

use App\AdminHistory;
use App\User;

use Illuminate\Http\Request;

use Auth;

class AdminHistoryController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        //Check page permission and redirect
        if( !Auth::user()->hasPagePermission('Admin History') )
            return redirect('/webadmin');

        $userId = $request->userId;
        if( is_null($userId) || $userId == 'all'){
            $userId = 'all';
            $adminHistories = AdminHistory::orderByDesc('created_at')->get();
        }
        else {
            $adminHistories = AdminHistory::orderByDesc('created_at')->where('user_id', $userId)->get();
        }

        $users          = User::get();
        return view("admin-history.index", [
            'currentSection'    => 'admin-history',
            'adminHistories'    => $adminHistories,
            'users'             => $users,
            'userId'            => $userId
        ]);
    }
}
