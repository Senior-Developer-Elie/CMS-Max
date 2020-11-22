<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use League\Flysystem\MountManager;
use Illuminate\Support\Facades\Storage;

use App\Blog;
use App\Comment;
use App\InnerBlogFile;
use App\TaskFile;
use App\Task;
use App\User;

class TransferFilesToS3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:transfer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer uploaded files to S3 Bucket';

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
        $mountManager = $this->getMountManager();

        $this->transferBlogs($mountManager);
        $this->transferComments($mountManager);
        $this->transferInnerBlogFiles($mountManager);
        $this->transferTaskFiles($mountManager);
        $this->transferUsers($mountManager);
        $this->transferTasksFavicons($mountManager);
    }

    /**
     * Get Mount Manager
     */
    public function getMountManager()
    {
        return new MountManager([
            's3' => Storage::disk('s3')->getDriver(),
            'public' => Storage::disk('public')->getDriver(),
        ]);
    }

    /**
     * Transfer Blog Files
     */
    public function transferBlogs($mountManager)
    {
        $blogs = Blog::all();
        foreach( $blogs as $blog ){
            if( !empty($blog->blog_url) && Storage::disk('public')->exists($blog->blog_url)
                && !Storage::disk('s3')->exists($blog->blog_url) ){
                $mountManager->move('public://' . $blog->blog_url, 's3://' . $blog->blog_url);
            }
            if( !empty($blog->blog_image) && Storage::disk('public')->exists($blog->blog_image)
                && !Storage::disk('s3')->exists($blog->blog_image) ){
                $mountManager->move('public://' . $blog->blog_image, 's3://' . $blog->blog_image);
            }
        }
    }

    /**
     * Transfer Comments
     */
    public function transferComments($mountManager)
    {
        $comments = Comment::all();
        foreach( $comments as $comment ){
            if( $comment->type == 'file' && !empty($comment->file_path) && Storage::disk('public')->exists($comment->file_path)
                && !Storage::disk('s3')->exists($comment->file_path) ){
                $mountManager->move('public://' . $comment->file_path, 's3://' . $comment->file_path);
            }
        }
    }


    /**
     * Transfer InnerBlog Files
     */
    public function transferInnerBlogFiles($mountManager)
    {
        $innerBlogFiles = InnerBlogFile::all();
        foreach( $innerBlogFiles as $innerBlogFile ){
            if( !empty($innerBlogFile->path) && Storage::disk('public')->exists($innerBlogFile->path)
                && !Storage::disk('s3')->exists($innerBlogFile->path) ){
                $mountManager->move('public://' . $innerBlogFile->path, 's3://' . $innerBlogFile->path);
            }
        }
    }

    /**
     * Transfer Task Files
     */
    public function transferTaskFiles($mountManager)
    {
        $taskFiles = TaskFile::all();
        foreach( $taskFiles as $taskFile ){
            if( !empty($taskFile->path) && Storage::disk('public')->exists($taskFile->path)
                && !Storage::disk('s3')->exists($taskFile->path) ){
                $mountManager->move('public://' . $taskFile->path, 's3://' . $taskFile->path);
            }
        }
    }

    /**
     * Transfer User Avatar
     */
    public function transferUsers($mountManager)
    {
        $users = User::all();
        foreach( $users as $user ){
            if( !empty($user->avatar) && Storage::disk('public')->exists($user->avatar)
                && !Storage::disk('s3')->exists($user->avatar) ){
                $mountManager->move('public://' . $user->avatar, 's3://' . $user->avatar);
            }
        }
    }

    /**
     * Transfer Favicons and social media icons for website in progress
     */
    public function transferTasksFavicons($mountManager)
    {
        $tasks = Task::all();
        foreach( $tasks as $task ){
            if( is_array($task->pre_live) ){
                foreach( $task->pre_live as $option ){
                    if( isset($option['uploaded_image']) && Storage::disk('public')->exists($option['uploaded_image'])
                        && !Storage::disk('s3')->exists($option['uploaded_image']) ){
                        $mountManager->move('public://' . $option['uploaded_image'], 's3://' . $option['uploaded_image']);
                    }
                }
            }
        }
    }
}
