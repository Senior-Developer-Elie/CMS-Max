<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\InnerBlog;
use App\Stage;

use App\Http\Helpers\BlogHelper;

use Carbon;

class BlogEmailOfTheDay extends Command
{
    const PENDING_BLOGS_TO_ADD_IMAGE_USER_ID = 6;
    const PENDING_BLOGS_TO_ADD_WEBSITE_USER_ID = 7;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:blog-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email notifications to blog images admins';

    /**
     * Stage data for email
     */
    protected $stageTexts = [
        'Quoting'               => 'We are currently quoting %u websites.',
        'Gathering Information' => 'We are currently gathering information for %u websites.',
        'Home Page Content'     => 'We are currently writing home page copy for %u websites.',
        'Design'                => 'We currently have %u websites to design.',
        'Website Core Setup'    => 'We are ready to setup %u websites.',
        'Inner Page Setup'      => 'We have %u websites to complete for inner pages.',
        'Final Checks'          => 'Please double check the following websites before we go live. All eyes needed.'
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Send email to blog images admins with pending Blogs to add images
        $this->sendTaskEmailsToAdmins();
    }

    public function sendTaskEmailsToAdmins()
    {
        $admins = \App\User::get();
        foreach( $admins as $admin ) {
            if (! $admin->email_notification_enabled && ! $admin->hasRole('super admin')) {
                continue;
            }
            $notifications = [];
            $totalCount = 0;

            if (true) {
                $pendingBlogsToWrite = BlogHelper::getPendingToWriteBlogs($admin);
                if( count($pendingBlogsToWrite) > 0 ) {
                    $detailLines = [];
                    forEach( $pendingBlogsToWrite as $blog ) {
                        if( !is_null($blog->website()) )
                            $detailLines[] = "<a href = '//" . $blog->website()->website . "'><strong>" . $blog->website()->name . "</strong></a> " . $blog->name;
                    }
                    $notifications[] = [
                        'text'          => 'There are ' . count($pendingBlogsToWrite) . ' Pending blogs to write.',
                        'href'          =>  url('/blog-list?blogType=pendingToWrite'),
                        'detailLines'   => $detailLines,
                    ];
                }
                $totalCount += count($pendingBlogsToWrite);
            }

            if (true) {
                if ($admin->id == self::PENDING_BLOGS_TO_ADD_IMAGE_USER_ID) {
                    $pendingBlogsToAddImage = BlogHelper::getPendingToAddImageBlogs($admin);
                    if( count($pendingBlogsToAddImage) > 0 )
                        $notifications[] = [
                            'text'  => 'There are ' . count($pendingBlogsToAddImage) . ' Pending blogs to add image.',
                            'href'  => url('/blog-list?blogType=pendingToAddImage')
                        ];
                    $totalCount += count($pendingBlogsToAddImage);
                }

                if ($admin->id == self::PENDING_BLOGS_TO_ADD_WEBSITE_USER_ID) {
                    $pendingBlogsToAddToWebsite = BlogHelper::getPendingToAddToWebsiteBlogs($admin);
                    if( count($pendingBlogsToAddToWebsite) > 0 )
                        $notifications[] = [
                            'text'  => 'There are ' . count($pendingBlogsToAddToWebsite) . ' Pending blogs to add to website.',
                            'href'  => url('/blog-list?blogType=pendingToAddToWebsite')
                        ];
                    $totalCount += count($pendingBlogsToAddToWebsite);
                }
            }

            /**Jobs by Stages Notification */
            foreach( $this->stageTexts as $key=>$stageText ) {
                $assignedTasks = Stage::where('name', $key)->first()->tasks()->get();

                if( count($assignedTasks) > 0 ) {
                    $notification = [
                        'text'  => sprintf($stageText, count($assignedTasks)),
                        'href'  => url('/website-progress')
                    ];
                    //If final check add detail lines
                    if( $key == 'Final Checks' ) {
                        $detailLines = [];
                        forEach( $assignedTasks as $task ) {
                            $detailLines[] = "<a href = '" . url('/website-progress?isUniqueView=true&activeTaskId=') . $task->id . "'><strong>" . $task->name . "</strong></a>";
                        }
                        $notification['detailLines'] = $detailLines;
                    }

                    $notifications[] = $notification;
                    $totalCount += count($assignedTasks);
                }
            }

            if( $totalCount > 0 ){ //Send Email
                \Mail::send('manage-blog.sections.task-notification-mail', ['notifications' => $notifications], function($message) use ($admin, $totalCount){
                    $message->from(env('MAIL_FROM_ADDRESS', 'info@cmsmax.com'), 'Evolution Marketing - CRM');
                    $message->to($admin->email, 'Evolution Marketing - CRM')->subject
                        ('You have ' . $totalCount . ' Pending Jobs to do');
                });
            }
        }
    }
}
