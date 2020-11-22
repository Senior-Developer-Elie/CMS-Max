<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\User;
use App\Website;
use App\InnerBlog;
use App\InnerBlogFile;
use App\AdminHistory;

use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Http\Helpers\NotificationHelper;

class InnerBlogController extends Controller
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
     * Inner Pages Dashboard
     */
    public function index(Request $request)
    {

        //Check page permission and redirect
        if( !Auth::user()->hasPagePermission('Jobs To Do') )
            return redirect('/webadmin');

        $enableDrag = $request->input('enableDrag');
        if( is_null($enableDrag) )
            $enableDrag = 'on';

        $websites    = Website::orderBy('name')->get();

        $filter             = $request->input('filter');
        $assigneeFilter     = $request->input('assignee');
        $sortColumn         = $request->input('sortColumn');
        $sortOrder         = $request->input('sortOrder');

        if( is_null($filter) )
            $filter = 'pending';
        if( is_null($assigneeFilter) )
            $assigneeFilter = "-1";
        if( is_null($sortColumn) )
            $sortColumn = 'priority';
        if( is_null($sortOrder) )
            $sortOrder = 'asc';


        if( Auth::user()->can('content manager') ) {
            if( $assigneeFilter == "-1" )
                $query = InnerBlog::where('id', '>', 0);
            else
                $query = InnerBlog::where('assignee_id', $assigneeFilter);
        }
        else
            $query = InnerBlog::where('assignee_id', Auth::user()->id);

        if( $filter == 'pending' ){
            $query = $query->where('marked', '!=', 1);

            if( $sortColumn == 'website_id' ) {
                if( $sortOrder == 'asc' ) {
                    $innerBlogs = $query->get()->sortBy(function($innerBlog) {
                        return $innerBlog->website()->name;
                    });
                }
                else {
                    $innerBlogs = $query->get()->sortByDesc(function($innerBlog) {
                        return $innerBlog->website()->name;
                    });
                }
            }
            else {
                if( $sortOrder == 'asc' )
                    $innerBlogs = $query->orderBy($sortColumn)->get();
                else
                    $innerBlogs = $query->orderByDesc($sortColumn)->get();
            }
        }
        else{
            $innerBlogs = $query->where('marked', 1)->orderByDesc('completed_at')->get();
        }

        $allAdmins = User::orderBy('name')->get();
        $adminsWithTasksAssigned = [];
        foreach( $allAdmins as $admin ){
            if( ($filter == 'pending' && count(InnerBlog::where('marked', 0)->where('assignee_id', $admin->id)->get()) > 0)
                OR ($filter == 'completed' && count(InnerBlog::where('marked', 1)->where('assignee_id', $admin->id)->get()) > 0) )
            {
                $adminsWithTasksAssigned[] = $admin;
            }
        }

        return view('inner-page.index', [
            'currentSection'    => 'inner-page',
            'filter'            => $filter,
            'assigneeFilter'    => $assigneeFilter,
            'sortColumn'        => $sortColumn,
            'sortOrder'         => $sortOrder,
            'websites'          => $websites,
            'admins'            => User::orderBy('name')->get(),
            'adminsWithTasks'   => $adminsWithTasksAssigned,
            'innerBlogs'        => $innerBlogs,
            'jobsToDoCount'     => $query->where('to_do', 1)->count(),
            'editInnerBlogId'   => is_null($request->input('editInnerBlogId')) ? "-1" : $request->input('editInnerBlogId'),
            'enableDrag'        => $enableDrag
        ]);
    }

    /**
     * Add/Edit Inner Page Task
     */
    public function addInnerPage(Request $request)
    {
        $innerPageId    = $request->input('inner_page_id');
        $jobTitle       = $request->input('title');
        $websiteId      = $request->input('website_id');
        $neededText     = $request->input('needed_text');
        $assigneeId     = $request->input('assignee_id');
        $dueDate        = $request->input('due_date');
        $isToDo         = $request->input('to_do') == 'true' ? true : false;

        if($innerPageId == '-1'){ //add inner page

            $data = [
                'website_id'    => $websiteId,
                'title'         => $jobTitle,
                'needed_text'   => $neededText,
                'assignee_id'   => $assigneeId,
                'to_do'         => $isToDo,
            ];
            if( !empty($dueDate) && $dueDate != 'null' )
                $data['due_date'] = $dueDate;
            else
                $data['due_date'] = null;

            $innerBlog = InnerBlog::createWithPriority($data);

            $innerBlogFileIds = explode(',', $request->input('innerBlogFileIds'));

            //Set inner blog id for uploaded files
            foreach( $innerBlogFileIds as $innerBlogFileId) {

                $innerBlogFile = InnerBlogFile::find($innerBlogFileId);
                if( !is_null($innerBlogFile) ) {
                    $innerBlogFile->inner_blog_id = $innerBlog->id;
                    $innerBlogFile->save();
                }
            }

            //Add Notification To Assignee
            NotificationHelper::addNotification([
                'type'          => 'assign job',
                'user_id'       => $assigneeId,
                'triggered_by'  => Auth::user()->id,
                'reference_id'  => $innerBlog->id,
            ]);

            //Add Admin History
            AdminHistory::addHistory([
                'user_id'   => Auth::user()->id,
                'type'      => 'add inner page',
                'message'   => 'Add inner page for Website ' . $innerBlog->website()->website,
                'ref'       => $innerBlog->id
            ]);

            Session::flash('message', 'Inner Page Task Added Successfully!');
            Session::flash('alert-class', 'alert-success');

            return response()
            ->json([
                'status'    => 'success'
            ]);
        }
        else {  //edit inner page
            $innerBlog = InnerBlog::find($innerPageId);

            if( is_null($innerBlog) ) {
                return response()
                    ->json([
                        'status'    => 'error'
                ]);

                Session::flash('message', 'Something went wrong while editing Inner Page');
                Session::flash('alert-class', 'alert-error');
            }

            //Add Notification To Assignee
            if( $assigneeId != $innerBlog->assignee_id ) {
                NotificationHelper::addNotification([
                    'type'          => 'assign job',
                    'user_id'       => $assigneeId,
                    'triggered_by'  => Auth::user()->id,
                    'reference_id'  => $innerBlog->id,
                ]);
            }

            $innerBlog->title       = $jobTitle;
            $innerBlog->website_id   = $websiteId;
            $innerBlog->needed_text = $neededText;
            $innerBlog->assignee_id = $assigneeId;
            $innerBlog->to_do       = $isToDo;
            if( !empty($dueDate) && $dueDate != 'null' )
                $innerBlog->due_date = $dueDate;
            $innerBlog->save();

            //Add Admin History
            AdminHistory::addHistory([
                'user_id'   => Auth::user()->id,
                'type'      => 'edit inner page',
                'message'   => 'Edit inner page for Website ' . $innerBlog->website()->website,
                'ref'       => $innerBlog->id
            ]);

            Session::flash('message', 'Inner Page Task Edited Successfully!');
            Session::flash('alert-class', 'alert-success');

            return response()
                    ->json([
                        'status'    => 'success'
            ]);
        }
    }

    /**
     * Update Inner Page Priority
     */
    public function updateInnerPagePriority(Request $request)
    {
        $priorities = $request->input('priorities');
        if( !is_array($priorities) )
        {
            return response()
                ->json([
                    'status'    => 'error'
            ]);
        }

        foreach( $priorities as $priority ) {
            $innerBlog = InnerBlog::find($priority['innerPageId']);
            if( !is_null($innerBlog) ){
                $innerBlog->priority = $priority['priority'];
                $innerBlog->save();
            }
        }

        return response()
            ->json([
                'status'    => 'success'
        ]);
    }

    /**
     * Return Inner Page Data
     */
    public function getInnerPageData(Request $request)
    {
        $innerPageId = $request->input('innerPageId');
        $innerBlog = InnerBlog::find($innerPageId);

        if( is_null($innerBlog) ){
            return response()
            ->json([
                'status'    => 'error'
            ]);
        }

        return response()
        ->json([
            'status'            => 'success',
            'innerPageData'     => $innerBlog->toArray(),
            'innerPageFiles'    => $innerBlog->files()->get()->toArray()
        ]);

    }

    /**
     * Delete Inner Page Data
     */
    public function deleteInnerPage(Request $request)
    {
        $innerPageId = $request->input('innerPageId');
        $innerBlog = InnerBlog::find($innerPageId);

        if( is_null($innerBlog) ){
            return response()
            ->json([
                'status'    => 'error'
            ]);
        }

        $innerBlog->delete();

        Session::flash('message', 'Inner Page Task Deleted Successfully!');
        Session::flash('alert-class', 'alert-success');

        return response()
        ->json([
            'status'        => 'success'
        ]);
    }

    /**
     * Complete Blog
     */
    public function completeInnerPage(Request $request)
    {
        $innerBlog = InnerBlog::find($request->input('innerPageId'));

        if( is_null($innerBlog) )
            return response()->json([
                'status'    => 'error'
            ]);

        $innerBlog->website = $request->input('website');
        $innerBlog->marked = true;
        $innerBlog->completed_by = Auth::user()->id;
        $innerBlog->completed_at = Carbon::now();
        $innerBlog->save();

        //Add Notification To managers
        NotificationHelper::addNotificationsToManagers([
            'type'          => 'complete job',
            'triggered_by'  => Auth::user()->id,
            'reference_id'  => $innerBlog->id,
        ]);

        //Add Admin History
        AdminHistory::addHistory([
            'user_id'   => Auth::user()->id,
            'type'      => 'complete inner task',
            'message'   => 'Inner Page Task : Complete Blog For Website ' . $innerBlog->website()->name,
            'ref'       => $innerBlog->id
        ]);

        Session::flash('message', 'Task completed successfully!');
        Session::flash('alert-class', 'alert-success');

        return response()->json([
            'status'    => 'success'
        ]);
    }

    /**
     * Download Files
     */
    public function downloadAllFiles(Request $request)
    {
        $innerBlog = InnerBlog::find($request->input('innerBlogId'));
        $innerBlogFile = InnerBlogFile::find($request->input('innerBlogFileId'));

        if( is_null($innerBlog) && is_null($innerBlogFile) ){
            return response()->json([
                'status'    => 'error'
            ]);
        }

        if( !is_null($innerBlog) ) {    //Download All InnerBlog Files

            $files = [];
            foreach( $innerBlog->files()->get() as $file ){
                $files[] = [
                    'public_url'    => Storage::url($file->path),
                    'origin_name'   => $file->origin_name
                ];
            }

            if( $innerBlog->website() )
                $downloadFileName = ( $innerBlog->website()->name ) . ".zip";
            else
                $downloadFileName = "temp.zip";

            return response()->json([
                'status'        => 'success',
                'downloadData'  => [
                    'type'          => 'zip',
                    'files'         => $files,
                    'zipFileName'   => $downloadFileName
                ]
            ]);
        }

        if( !is_null($innerBlogFile) ){

            return response()->json([
                'status'        => 'success',
                'downloadData'  => [
                    'type'  => 'single',
                    'public_url'   => Storage::url($innerBlogFile->path),
                    'file_name'     => $innerBlogFile->origin_name
                ]
            ]);
        }
    }

    public function undoComplete(Request $request)
    {
        $innerBlog = InnerBlog::find($request->input('innerPageId'));

        if( is_null($innerBlog) )
            return response()->json([
                'status'    => 'error'
            ]);

        $innerBlog->marked = false;
        $innerBlog->save();

        Session::flash('message', 'Blog status has been updated!');
        Session::flash('alert-class', 'alert-success');

        return response()->json([
            'status'    => 'success'
        ]);
    }

    /**
     * File Add Method
     */
    public function uploadFiles(Request $request)
    {
        $innerBlogId = $request->input('innerPageId');
        $innerBlog = InnerBlog::find($request->input('innerPageId'));

        if( (is_null($innerBlog) && $innerBlogId != "-1")|| !$request->hasFile('files') ){
            return response()->json([
                'status'    => 'error'
            ]);
        }

        $files = request()->file('files');
        $filesData = [];
        foreach( $files as $file )
        {
            $filePath = $file->store('inner-page-files');
            $innerBlogFile = new InnerBlogFile([
                'inner_blog_id' => $innerBlogId,
                'origin_name'   => $file->getClientOriginalName(),
                'path'          => $filePath,
                'file_type'     => substr($file->getMimeType(), 0, 5) == 'image' ? 'image' : 'blog'
            ]);
            $innerBlogFile->save();

            $filesData[] = $innerBlogFile->toArray();
        }

        return response()->json([
            'status'    => 'success',
            'files'     => $filesData
        ]);
    }

    /**
     * Clear Blog Image
     */
    public function clearUploadedFile(Request $request)
    {
        $innerBlogFile = InnerBlogFile::find($request->input('innerBlogFileId'));

        if( is_null($innerBlogFile) ) {
            return response()
            ->json([
                'status'    => 'error'
            ]);
        }

        $innerBlogFileId = $innerBlogFile->id;
        $innerBlogFile->delete();
        return response()
            ->json([
                'status'            => 'success',
                'innerBlogFileId'   => $innerBlogFileId
            ]);
    }

    /**
     * Mark file as final file
     */
    public function markFileAsFinal(Request $request)
    {
        $innerBlogFile = InnerBlogFile::find($request->input('innerBlogFileId'));

        if( is_null($innerBlogFile) ) {
            return response()
            ->json([
                'status'    => 'error'
            ]);
        }

        $innerBlogFileId = $innerBlogFile->id;
        $innerBlogFile->status = 'final';
        $innerBlogFile->save();

        return response()
            ->json([
                'status'            => 'success',
                'innerBlogFileId'   => $innerBlogFileId
            ]);

    }

    /**
     * Mark file as pending
     */
    public function markFileAsPending(Request $request)
    {
        $innerBlogFile = InnerBlogFile::find($request->input('innerBlogFileId'));

        if( is_null($innerBlogFile) ) {
            return response()
            ->json([
                'status'    => 'error'
            ]);
        }

        $innerBlogFileId = $innerBlogFile->id;
        $innerBlogFile->status = 'pending';
        $innerBlogFile->save();

        return response()
            ->json([
                'status'            => 'success',
                'innerBlogFileId'   => $innerBlogFileId
            ]);

    }
}
