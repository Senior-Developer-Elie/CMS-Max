<?php

namespace App\Http\Controllers;

use App\BlogIndustry;
use Illuminate\Http\Request;

use Auth;
use Session;

class BlogIndustryController extends Controller
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
     * Blog Industries List
     */
    public function index(Request $request)
    {
        //Check page permission and redirect
        if( !Auth::user()->hasPagePermission('Manage Industries') )
            return redirect('/webadmin');

        $blogIndustries = BlogIndustry::orderBy('name')->with('websites')->get();
        return view('blog-industries.index', [
            'currentSection'    => 'blog-industries',
            'blogIndustries'    => $blogIndustries
        ]);
    }

    /**
     * Add/Edit Blog Industry
     */
    public function addBlogIndustry(Request $request)
    {
        $blogIndustryId = $request->input('blogIndustryId');
        $name           = $request->input('name');

        if( $blogIndustryId == "-1" ) { //if add
            BlogIndustry::create([
                'name'  => $name
            ]);

            Session::flash('message', 'Industry added successfully!');
            Session::flash('alert-class', 'alert-success');

            return response()
                ->json([
                    'status'    => 'success'
                ]);
        }
        else {
            $blogIndustry = BlogIndustry::find($blogIndustryId);

            if( is_null($blogIndustry) )
            {
                return response()
                ->json([
                    'status'    => 'error'
                ]);
            }
            $blogIndustry->name = $name;

            $blogIndustry->save();

            Session::flash('message', 'Industry edited successfully!');
            Session::flash('alert-class', 'alert-success');

            return response()
                ->json([
                    'status'    => 'success'
                ]);
        }
    }

    /**
     * Delete Blog Industry
     */
    public function deleteBlogIndustry(Request $request)
    {
        $blogIndustry = BlogIndustry::find($request->input('blogIndustryId'));

        if( is_null($blogIndustry) ) {
            return response()
                ->json([
                    'status'    => 'error'
                ]);
        }

        $blogIndustry->delete();

        Session::flash('message', 'Industry deleted successfully!');
        Session::flash('alert-class', 'alert-success');

        return response()
            ->json([
                'status'    => 'success'
            ]);
    }

    /**
     * Assigned clients list
     */
    public function clientList(Request $request)
    {
        $blogIndustryId = $request->input('blogIndustryId');
        $blogIndustry = BlogIndustry::find($blogIndustryId);

        if( is_null($blogIndustry) )
            abort(404);

        return view('blog-industries.website-list', [
            'currentSection'    => 'blog-industries',
            'blogIndustry'    => $blogIndustry
        ]);
    }
}
