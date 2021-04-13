<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Blog;
use App\AdminHistory;
use App\Website;


use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

use App\Http\Helpers\BlogHelper;
use App\Http\Helpers\NotificationHelper;
use App\User;

class BlogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {

            if( !Auth::user()->can('manage blogs') && !Auth::user()->can('blog images') && !Auth::user()->can('content manager') && !Auth::user()->can('writer'))
                return redirect('/webadmin');
            return $next($request);
        });
    }

    /**
     * Blog Dashboard
     */
    public function index(Request $request)
    {
        //Check page permission and redirect
        if( !Auth::user()->hasPagePermission('Blog Dashboard') )
            return redirect('/webadmin');

        //Get Blog Clients
        $websites = $this->getWebsites();

        //Get Future Months
        $futureMonths = BlogHelper::getFutureMonths();

        $prettyWebsites = [];
        $pendingBlogsToAddTitle = 0;
        $pendingBlogsToWrite = 0;
        $pendingBlogsToAddImage = 0;
        $pendingBlogsToAddToWebsite  = 0;
        $blogsDone = 0;

        $totalBlogsForMonth = array_fill(0, count($futureMonths), 0);
        foreach( $websites as $website ){

            $futureBlogs = $website->futureBlogs();
            $availableMonths = $website->availableMonths();

            $prettyFutureBlogs = [];    //Fill out empty months as well

            foreach( $futureMonths as $index => $futureMonth ) {

                $prettyFutureBlog = [
                    'class'     => 'not-available',
                    'blogName'  => 'N/A'
                ];

                $blogExist = false;
                foreach( $futureBlogs as $blog ) {
                    $blogDate = (new Carbon($blog->desired_date))->startOfMonth();
                    if( $blogDate->diffInMonths($futureMonth) == 0 )    //If same month
                    {
                        $blogExist = $blog;
                        break;
                    }
                }
                if( $blogExist === false ){
                    if( in_array($futureMonth, $availableMonths) ) { //if this is availble month
                        $prettyFutureBlog = [
                            'class'     => 'empty',
                            'blogName'  => ''
                        ];
                        $pendingBlogsToAddTitle++;

                    }
                    else{
                        $prettyFutureBlog = [
                            'class'     => 'not-available',
                            'blogName'  => 'N/A'
                        ];
                    }
                }
                else {
                    if(strtolower(trim($blogExist->name)) == 'n/a') //If set manually n/a
                    {
                        $prettyFutureBlog['class'] = 'not-available';
                        $prettyFutureBlog = [
                            'class'     => 'not-available',
                            'blogName'  => 'N/A'
                        ];
                    }
                    else
                    {
                        $prettyFutureBlog['blog'] = $blog;

                        if( is_null($blog->blog_url) || $blog->blog_url === '' ){

                            if( trim($blog->name) == '' ){
                                $prettyFutureBlog['class'] = 'empty';
                                $prettyFutureBlog['blogName'] = '';

                                $pendingBlogsToAddTitle++;
                            }
                            else {
                                $prettyFutureBlog['class'] = 'normal';
                                $prettyFutureBlog['blogName'] = $blog->name;
                                $pendingBlogsToWrite++;
                            }
                        }
                        else{

                            if( is_null($blog->blog_image) || $blog->blog_image === '' ){
                                $prettyFutureBlog['class'] = 'pending-to-add-image';
                                $prettyFutureBlog['blogName'] = 'Pending To Add Image';
                                $pendingBlogsToAddImage++;
                            }
                            else {
                                if( $blog->marked == false) {
                                    $prettyFutureBlog['class'] = 'pending';
                                    $prettyFutureBlog['blogName'] = 'Pending To Add To Website';
                                    $pendingBlogsToAddToWebsite++;
                                }
                                else {
                                    $prettyFutureBlog['class'] = 'done';
                                    $prettyFutureBlog['blogName'] = 'Done';
                                    $blogsDone++;
                                }
                            }
                        }
                    }
                }

                $prettyFutureBlogs[] = $prettyFutureBlog;

                if( $prettyFutureBlog['class'] != 'not-available' )
                    $totalBlogsForMonth[$index]++;
            }

            $prettyWebsites[] = [
                'website'               => $website,
                'futureBlogs'           => $prettyFutureBlogs,
                'availableMonths'       => $availableMonths,
            ];
        }

        $data = [
            'currentSection'                => 'blog-dashboard',
            'initialExpandOnHover'          => true,
            'websites'                      => $prettyWebsites,
            'pendingBlogsToAddTitle'        => count(BlogHelper::getPendingToAddTitleBlogs(Auth::user())),
            'pendingBlogsToWrite'           => count(BlogHelper::getPendingToWriteBlogs(Auth::user())),
            'pendingBlogsToAddImage'        => count(BlogHelper::getPendingToAddImageBlogs(Auth::user())),
            'pendingBlogsToAddToWebsite'    => count(BlogHelper::getPendingToAddToWebsiteBlogs(Auth::user())),
            'blogsDone'                     => $blogsDone,
            'futureMonths'                  => $futureMonths,
            'totalBlogsForMonth'            => $totalBlogsForMonth,
            'isBlogManager'                 => Auth::user()->can('content manager'),
            'users'                         => User::where('type', '!=', User::USER_TYPE_CMS_MAX_DEVELOPER)->orderBy('name')->get(),
        ];

        return view('manage-blog/blog-dashboard', $data);
    }

    /**
     * Change Blog Name
     * API
     */
    public function changeBlogName(Request $request)
    {
        $websiteId       = $request->websiteId;
        $desiredDate    = new Carbon($request->desiredDate);
        $blogName       = $request->blogName;

        //Check if blog exist
        $blogExist = Blog::where('website_id', $websiteId)
                        ->where('desired_date', '>=', (string)$desiredDate->startOfMonth())
                        ->where('desired_date', '<=', (string)$desiredDate->endOfMonth())
                        ->first();
        if( is_null($blogExist) ) {  //If not exist then create
            $blogExist = new Blog([
                'website_id'    => $websiteId,
                'name'          => $blogName,
                'desired_date'  => $desiredDate->startOfMonth()
            ]);
            $blogExist->save();
        }
        else {
            $blogExist->name = $blogName;
            $blogExist->save();
        }

        //Add Admin History
        AdminHistory::addHistory([
            'user_id'   => Auth::user()->id,
            'type'      => 'change blog name',
            'message'   => 'Change Blog Name For Website ' . $blogExist->website()->website . ' For ' . (new Carbon($blogExist->desired_date))->format('M Y'),
            'ref'       => $blogExist->id
        ]);

        return response()
            ->json([
                'status'    => 'success'
            ]);
    }

    /**
     * Blog List
     */
    public function blogList(Request $request)
    {
        $blogType   = $request->get('blogType');
        $data = [
            'currentSection'    => 'blog-dashboard'
        ];

        $blogs      = [];
        $emptyBlogs = [];

        if( is_null($blogType) )
            abort(404);
        if( $blogType == 'done' ) {
            $blogs = BlogHelper::getDoneBlogs(Auth::user());
        }
        else if( $blogType == 'pendingToWrite' ) {
            $blogs = BlogHelper::getPendingToWriteBlogs(Auth::user());
        }
        else if( $blogType == 'pendingToAddImage' ) {
            $blogs = BlogHelper::getPendingToAddImageBlogs(Auth::user());
        }
        else if( $blogType == 'pendingToAddToWebsite' ) {
            $blogs = BlogHelper::getPendingToAddToWebsiteBlogs(Auth::user());
        }
        else if( $blogType == 'pendingToAddTitle' ) {
            $emptyBlogs = BlogHelper::getPendingToAddTitleBlogs(Auth::user());
        }
        $headingText = '';
        if( !is_null($blogType) ) {
            if( $blogType == 'done' ) {
                $headingText = 'Blogs Done : ' . count($blogs);
            }
            else if( $blogType == 'pendingToWrite' ) {
                $headingText = 'Blogs Pending To Write : ' . count($blogs);
            }
            else if( $blogType == 'pendingToAddImage' ) {
                $headingText = 'Blogs Pending To Add Image : ' . count($blogs);
            }
            else if( $blogType == 'pendingToAddToWebsite' ) {
                $headingText = 'Blogs Pending To Add To Website : ' . count($blogs);
            }
            else if( $blogType == 'pendingToAddTitle' ) {
                $headingText = 'Blogs Pending To Add Title : ' . count($emptyBlogs);
            }
        }

        $data['headingText']    = $headingText;
        $data['blogs']          = $blogs;
        $data['emptyBlogs']     = $emptyBlogs;
        return view("manage-blog.blog-list", $data);
    }

    /**
     * Mark Blog as Complete
     */
    public function markComplete(Request $request, $blog_id)
    {
        $blog = Blog::find($blog_id);
        if( !$blog )
            abort(404);
        if( $request->isMethod('get') ) {

            //if( !is_null($request->get('backUrl')) )
                //Session::flash('backUrl', $request->get('backUrl'));

            $data = [
                'currentSection'    => 'blog-dashboard',
                'blog'              => $blog
            ];
            return view("manage-blog.mark-complete", $data);
        }
        else if( $request->isMethod('post') ){
            $blog->marked = true;
            $blog->blog_website = $request->blog_website;
            $blog->completed_by = Auth::user()->id;
            $blog->completed_at = Carbon::now();
            $blog->save();

            Session::flash('message', 'Blog completed successfully!');
            Session::flash('alert-class', 'alert-success');

            //Add Notification To managers
            NotificationHelper::addNotificationsToManagers([
                'type'          => 'complete blog',
                'triggered_by'  => Auth::user()->id,
                'reference_id'  => $blog->id,
            ]);

            //Add Admin History
            AdminHistory::addHistory([
                'user_id'   => Auth::user()->id,
                'type'      => 'complete blog',
                'message'   => 'Complete Blog For Website ' . $blog->website()->name . ' For ' . (new Carbon($blog->desired_date))->format('M Y'),
                'ref'       => $blog->id
            ]);

            //if( Session::has('backUrl') )
                //return redirect(Session::get('backUrl'));
            //else
                return redirect('/blog-dashboard');
        }
    }

    /**
     * Undo blog completed action
     */
    public function undoComplete(Request $request, $blog_id)
    {
        $blog = Blog::find($blog_id);
        if( !$blog )
            abort(404);
        if( $request->isMethod('get') ) {

            //if( !is_null($request->get('backUrl')) )
                //Session::flash('backUrl', $request->get('backUrl'));

            $data = [
                'currentSection'    => 'blog-dashboard',
                'blog'              => $blog
            ];
            return view("manage-blog.undo-complete", $data);
        }
        else if( $request->isMethod('post') ){
            $blog->marked = false;
            $blog->save();

            Session::flash('message', 'Blog status has been updated!');
            Session::flash('alert-class', 'alert-success');

            //if( Session::has('backUrl') )
                //return redirect(Session::get('backUrl'));
            //else
                return redirect('/blog-dashboard');
        }
    }

    /**
     * Upload Blog
     */
    public function uploadBlog(Request $request)
    {
        $blog = Blog::find($request->blogId);
        if( !$blog )
            abort(404);
        if( $request->isMethod('get') ) {

            if( !is_null($request->get('backUrl')) )
                Session::flash('backUrl', $request->get('backUrl'));

            $data = [
                'currentSection'    => 'blog-dashboard',
                'blog'              => $blog
            ];

            return view("manage-blog.upload-blog", $data);
        }
        else if( $request->isMethod('post') ) {

            if ( !$request->hasFile('blogFile') ) {     //check if file exist
                Session::flash('message', 'Please select blog file!');
                Session::flash('alert-class', 'alert-error');
                return redirect('upload-blog/' . $blog->id);
            }

            //Remove Original if exist from storage
            $this->removeStorageFile($blog->blog_url);

            $blog->blog_url = request()->file('blogFile')->store('blogs');
            $blog->save();

            //Add Admin History
            AdminHistory::addHistory([
                'user_id'   => Auth::user()->id,
                'type'      => 'upload blog',
                'message'   => 'Upload Blog For Website ' . $blog->website()->name . ' For ' . (new Carbon($blog->desired_date))->format('M Y'),
                'ref'       => $blog->id
            ]);

            Session::flash('message', 'Blog uploaded successfully!');
            Session::flash('alert-class', 'alert-success');

            return response()->json([
                'status'    => 'success'
            ]);
            /*
            if( Session::has('backUrl') )
                return redirect(Session::get('backUrl'));
            else
                return redirect('/blog-dashboard');*/
        }
    }

    /**
     * Upload Blog Image
     */
    public function uploadBlogImage(Request $request)
    {
        $blog = Blog::find($request->blogId);
        if( !$blog )
            abort(404);
        if( $request->isMethod('get') ) {

            if( !is_null($request->get('backUrl')) )
                Session::flash('backUrl', $request->get('backUrl'));

            $data = [
                'currentSection'    => 'blog-dashboard',
                'blog'              => $blog
            ];

            return view("manage-blog.upload-blog-image", $data);
        }
        else if( $request->isMethod('post') ) {

            if ( !$request->hasFile('blogImageFile') ) {     //check if file exist
                Session::flash('message', 'Please select blog image file!');
                Session::flash('alert-class', 'alert-error');
                return redirect('upload-blog/' . $blog->id);
            }

            //Remove Original Images if exist
            if( !is_null($blog->blog_image) ){
                foreach( explode(',', $blog->blog_image) as $path) {
                    $this->removeStorageFile($path);
                }
            }

            $imagePaths = [];

            //Save Image File`
            if ($request->hasFile('blogImageFile')) {
                $files = request()->file('blogImageFile');

                foreach( $files as $file )
                {
                    $imagePaths[] = $file->store('blogImages');
                }
            }
            $blog->blog_image = implode(",", $imagePaths);

            $blog->save();

            //Add Admin History
            AdminHistory::addHistory([
                'user_id'   => Auth::user()->id,
                'type'      => 'upload image',
                'message'   => 'Upload Image For Website ' . $blog->website()->name . ' For ' . (new Carbon($blog->desired_date))->format('M Y'),
                'ref'       => $blog->id
            ]);

            Session::flash('message', 'Blog image uploaded successfully!');
            Session::flash('alert-class', 'alert-success');

            return response()->json([
                'status'    => 'success'
            ]);

            /*
            if( Session::has('backUrl') )
                return redirect(Session::get('backUrl'));
            else
                return redirect('/blog-dashboard');
            */
        }
    }

    /**
     * Clear Upload
     */
    public function clearUpload(Request $request, $blogId)
    {
        $blog = Blog::find($blogId);
        if( !$blog )
            abort(404);
        if( $request->isMethod('get') ) {

            if( !is_null($request->get('backUrl')) )
                Session::flash('backUrl', $request->get('backUrl'));

            $data = [
                'currentSection'    => 'blog-dashboard',
                'blog'              => $blog
            ];

            return view("manage-blog.clear-upload", $data);
        }
        else if( $request->isMethod('post') ) {

            //Remove Uploaded File From Storage
            $this->removeStorageFile($blog->blog_url);

            if( !is_null($blog->blog_image) ){
                foreach( explode(',', $blog->blog_image) as $path) {
                    $this->removeStorageFile($path);
                }
            }

            $blog->blog_url = '';
            $blog->blog_image = '';
            $blog->marked = false;
            $blog->save();

            Session::flash('message', 'Uploaded Files Cleared Successfully!');
            Session::flash('alert-class', 'alert-success');

            if( Session::has('backUrl') )
                return redirect(Session::get('backUrl'));
            else
                return redirect('/blog-dashboard');
        }
    }

    /**
     * Clear Blog Image
     */
    public function clearBlogImage(Request $request)
    {
        $blogId = $request->input('blogId');
        $blog = Blog::find($blogId);

        if( is_null($blog) ) {
            return response()
            ->json([
                'status'    => 'error'
            ]);
        }

        if( !is_null($blog->blog_image) ){
            foreach( explode(',', $blog->blog_image) as $path) {
                $this->removeStorageFile($path);
            }
        }

        $blog->blog_image = "";
        $blog->save();

        return response()
            ->json([
                'status'    => 'success'
        ]);
    }

    /**
     * Change Blog To N/A
     */
    public function changeToNotAvailable(Request $request, $blogId)
    {
        $blog = Blog::find($blogId);
        if( !$blog )
            abort(404);
        if( $request->isMethod('get') ) {

            if( !is_null($request->get('backUrl')) )
                Session::flash('backUrl', $request->get('backUrl'));

            $data = [
                'currentSection'    => 'blog-dashboard',
                'blog'              => $blog
            ];

            return view("manage-blog.change-to-not-available", $data);
        }
        else if( $request->isMethod('post') ) {

            $blog->name = 'N/A';
            $blog->blog_url = '';
            $blog->marked = false;
            $blog->save();

            Session::flash('message', 'Blog Set To N/A Successfully!');
            Session::flash('alert-class', 'alert-success');

            if( Session::has('backUrl') )
                return redirect(Session::get('backUrl'));
            else
                return redirect('/blog-dashboard');
        }
    }

    /**
     * Download blog
     */
    public function downloadBlog(Request $request, $blogId, $downloadType)
    {
        $blog = Blog::find($blogId);
        if( !$blog )
            abort(404);

        $file_full_path = '';
        if( $downloadType == 'blog' )
        {
            $path_info = pathinfo($blog->blog_url);
            $downloadFileName = str_replace('?', '', $blog->name) . " " . (new Carbon($blog->desired_date))->format('M Y') . "." . $path_info['extension'];

            $fs = Storage::getDriver();
            $stream = $fs->readStream($blog->blog_url);
            return \Response::stream(function() use($stream) {
                fpassthru($stream);
            }, 200, [
                "Content-Type" => $fs->getMimetype($blog->blog_url),
                "Content-Length" => $fs->getSize($blog->blog_url),
                "Content-disposition" => "attachment; filename=\"" . $downloadFileName . "\"",
                ]);
        }
        else if( $downloadType == 'image' ){

            $zipname = 'blog-images.zip';
            $zip = new \ZipArchive;
            $zip->open($zipname, \ZipArchive::CREATE);

            $files = [];
            if( !is_null($blog->blog_image) ){
                foreach( explode(',', $blog->blog_image) as $index=>$path) {
                    $filePath = Storage::url($path);
                    $path_info = pathinfo($filePath);
                    $zip->addFromString(str_replace('/', '', str_replace('?', '', $blog->name)) . (count(explode(',', $blog->blog_image)) == 1 ? '' : (' - ' . ($index+1))) . '.' . $path_info['extension'], Storage::disk('s3')->get($path));
                }
            }
            $downloadFileName = $blog->name . " " . (new Carbon($blog->desired_date))->format('M Y') . ".zip";
            $zip->close();
            $file_full_path = $zipname;
        }
        else if( $downloadType == 'both' ){
            $zipname = 'blog-files.zip';
            $zip = new \ZipArchive;
            $zip->open($zipname, \ZipArchive::CREATE);

            $filePath = Storage::url($blog->blog_url);
            $path_info = pathinfo($filePath);
            $zip->addFromString(str_replace('/', '', str_replace('?', '', $blog->name)) . '.' . $path_info['extension'], Storage::disk('s3')->get($blog->blog_url));
            $zip->addEmptyDir('images');

            if( !is_null($blog->blog_image) ){
                foreach( explode(',', $blog->blog_image) as $index=>$path) {
                    $filePath = Storage::url($path);
                    $path_info = pathinfo($filePath);
                    $zip->addFromString('images/' . str_replace('/', '', str_replace('?', '', $blog->name)) . (count(explode(',', $blog->blog_image)) == 1 ? '' : (' - ' . ($index+1))) . '.' . $path_info['extension'], Storage::disk('s3')->get($path));
                }
            }

            $zip->close();
            $file_full_path = $zipname;
        }

        // Download File
        $path_info = pathinfo($file_full_path);

        if( !file_exists($file_full_path) )
            abort(404);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=\"" . $blog->name . " " . (new Carbon($blog->desired_date))->format('M Y') . "." . $path_info['extension'] ."\"");
        header("Content-Transfer-Encoding: binary ");
        readfile($file_full_path);

        if( $downloadType == 'image' || $downloadType == 'both' )
            unlink($zipname);
    }

    /**
     * Get Carbon object from month and year
     */
    public static function getCarbonFromYearMonth($dateStr)
    {
        $dateMonthArray = explode('/', $dateStr);
        $month = $dateMonthArray[0];
        $year = $dateMonthArray[1];

        return (Carbon::createFromDate($year, $month, 1))->startOfMonth();
    }

    /**
     * Remove Storage File
     */
    protected function removeStorageFile($url) {

        if( !is_null($url) && $url !== '' && Storage::disk('s3')->exists($url)) {
            Storage::delete($url);
        }
    }

    protected function getWebsites()
    {
        $query = Website::where('is_blog_client', 1)->where('archived', 0)->orderBy('name');
        
        if (empty($userId = request()->input('user_id'))) {
            $userId = Auth::user()->id;
        }

        if ($userId != 'all') {
            $query->where('assignee_id', $userId);
        }

        return $query->get();
    }
}
