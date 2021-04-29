<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\SocialMediaCheckList;
use App\Validators\SocialMediaCheckListValidator;

class SocialMediaCheckListsController extends Controller
{
    protected $data = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->data['currentSection'] = 'manage-social-media-check-list';
    }

    public function index(Request $request)
    {
        //Check page permission and redirect
        if (! Auth::user()->hasPagePermission('Social Media CheckLists')) {
            return redirect('/webadmin');
        }

        return view('social_media_check_lists.index', $this->data);
    }

    public function create(Request $request)
    {
        return view('social_media_check_lists.create', $this->data);
    }

    public function store(Request $request)
    {
        // Sanitize
        $data = $request->all();

        // Validate
        $validator = new SocialMediaCheckListValidator();
        if (! $validator->validate($data)) {
            return redirect()->back()->withInput($data)->withErrors($validator->getErrors());
        }

        SocialMediaCheckList::create($data);

        Session::flash('message', 'Social Media Check List created successfully.');
        Session::flash('alert-class', 'alert-success');

        return redirect()->route('social_media_check_lists.index');
    }
    
    public function edit(SocialMediaCheckList $socialMediaCheckList, Request $request)
    {
        $this->data['socialMediaCheckList'] = $socialMediaCheckList;

        return view('social_media_check_lists.edit', $this->data);
    }

    public function update(SocialMediaCheckList $socialMediaCheckList, Request $request)
    {
        $data = $request->all();

        // Validate
        $validator = new SocialMediaCheckListValidator();
        if (! $validator->validate($data, 'update')) {
            return redirect()->back()->withInput($data)->withErrors($validator->getErrors());
        }

        $socialMediaCheckList->fill($data);
        $socialMediaCheckList->save();

        Session::flash('message', 'Social Media Check List updated successfully.');
        Session::flash('alert-class', 'alert-success');

        return redirect()->route('social_media_check_lists.index');
    }
}
