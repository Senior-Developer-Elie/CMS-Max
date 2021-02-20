<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\InnerBlog;
use App\Stage;

use App\Http\Helpers\BlogHelper;

use Carbon;

class BlogEmailOfTheDay extends Command
{
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
        'Quoting'               => 'You are currently quoting %u websites.',
        'Gathering Information' => 'You need to gather information for %u websites.',
        'Home Page Content'     => 'You need to write home page copy for %u websites.',
        'Design'                => 'You have %u websites to design.',
        'Website Core Setup'    => 'You have to setup %u websites.',
        'Inner Page Setup'      => 'You have %u websites to complete for inner pages.',
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
            if (! $admin->email_notification_enabled) {
                continue;
            }
            $notifications = [];
            $totalCount = 0;

            if( $admin->can('writer') ) {

                $pendingBlogsToAddTitle = BlogHelper::getPendingToAddTitleBlogs($admin);
                if( count($pendingBlogsToAddTitle) > 0 )
                    $notifications[] = [
                        'text'  => 'You have ' . count($pendingBlogsToAddTitle) . ' Pending blogs to add title.',
                        'href'  => url('/blog-list?blogType=pendingToAddTitle')
                    ];
                $totalCount += count($pendingBlogsToAddTitle);

                $pendingBlogsToWrite = BlogHelper::getPendingToWriteBlogs($admin);
                if( count($pendingBlogsToWrite) > 0 ) {
                    $detailLines = [];
                    forEach( $pendingBlogsToWrite as $blog ) {
                        if( !is_null($blog->website()) )
                            $detailLines[] = "<a href = '//" . $blog->website()->website . "'><strong>" . $blog->website()->name . "</strong></a> " . $blog->name;
                    }
                    $notifications[] = [
                        'text'          => 'You have ' . count($pendingBlogsToWrite) . ' Pending blogs to write.',
                        'href'          =>  url('/blog-list?blogType=pendingToWrite'),
                        'detailLines'   => $detailLines,
                    ];
                }
                $totalCount += count($pendingBlogsToWrite);
            }

            if( $admin->can('blog images') || $admin->can('content manager') ) {
                $pendingBlogsToAddImage = BlogHelper::getPendingToAddImageBlogs($admin);
                if( count($pendingBlogsToAddImage) > 0 )
                    $notifications[] = [
                        'text'  => 'You have ' . count($pendingBlogsToAddImage) . ' Pending blogs to add image.',
                        'href'  => url('/blog-list?blogType=pendingToAddImage')
                    ];
                $totalCount += count($pendingBlogsToAddImage);

                $pendingBlogsToAddToWebsite = BlogHelper::getPendingToAddToWebsiteBlogs($admin);
                if( count($pendingBlogsToAddToWebsite) > 0 )
                    $notifications[] = [
                        'text'  => 'You have ' . count($pendingBlogsToAddToWebsite) . ' Pending blogs to add to website.',
                        'href'  => url('/blog-list?blogType=pendingToAddToWebsite')
                    ];
                $totalCount += count($pendingBlogsToAddToWebsite);
            }

            $pendingJobsToDo = InnerBlog::where('assignee_id', $admin->id)
                                        ->where('marked', '!=', 1)
                                        ->where('to_do', 1)->get();
                                        if( count($pendingJobsToDo) > 0 )
            $notifications[] = [
                'text'  => 'You have ' . count($pendingJobsToDo) . ' Pending tasks assigned to you.',
                'href'  => url('/jobs')
            ];
            $totalCount += count($pendingJobsToDo);


            /**Jobs by Stages Notification */
            foreach( $this->stageTexts as $key=>$stageText ) {
                //Get assigned stage tasks for user
                if( $admin->hasRole('super admin') )
                    $assignedTasks = Stage::where('name', $key)->first()->tasks()->get();
                else
                    $assignedTasks = Stage::where('name', $key)->first()->tasks()->where('assignee_id', $admin->id)->get();

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
