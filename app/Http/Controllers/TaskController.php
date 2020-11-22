<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;
use App\TaskFile;
use App\Comment;
use App\User;

use App\Events\CommentCreatedEvent;
use App\Events\CommentRemovedEvent;
use App\Events\CommentPinEvent;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class TaskController extends Controller
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

    public function updateTaskPriorities(Request $request)
    {
        $priorities = $request->input('priorities');
        foreach( $priorities as $priority ) {
            $task = Task::find($priority['taskId']);
            if( !is_null($task) ) {
                $task->stage_id = $priority['stageId'];
                $task->priority = $priority['priority'];
                $task->save();
            }
        }
        return response()->json([
            'status'    => 'success'
        ]);
    }

    public function addTask(Request $request)
    {
        $stageId = $request->input('stageId');
        $task = new Task([
            'stage_id'  => $stageId
        ]);
        $task->save();

        return response()->json([
            'status'    => 'success',
            'taskId'    => $task->id
        ]);
    }

    public function updateTaskAttribute(Request $request)
    {
        $task = Task::find($request->input('pk'));
        if( is_null($task) ){
            return response()->json([
                'status'    => 'error'
            ]);
        }
        $attributeName = $request->input('name');
        $task->$attributeName = $request->input('value');
        $task->save();

        return response()->json([
            'status'    => 'success'
        ]);
    }

    public function getTaskDetails(Request $request)
    {
        $task = Task::find($request->input('taskId'));
        if( is_null($task) ) {
            return response()->json([
                'status'    => 'error'
            ]);
        }

        $taskDetails = $task->toArray();
        $taskDetails['files'] = [];

        $taskFiles = $task->files()->get();
        foreach( $taskFiles as $file ) {
            $taskDetails['files'][] = $file->toArray();
        }

        if( is_array($taskDetails['pre_live']) ) {
            foreach( $taskDetails['pre_live'] as $option => $data ){

                if( isset($data['uploaded_by']) || isset($data['checked_by']) )
                    $taskDetails['pre_live'][$option] = [];

                if( isset($data['uploaded_by']) ){
                    $uploaded_by_user = User::find($data['uploaded_by']);

                    $taskDetails['pre_live'][$option]['uploaded_by'] = is_null($uploaded_by_user) ? "" : $uploaded_by_user->name;
                    $taskDetails['pre_live'][$option]['uploaded_at'] = is_null($data['uploaded_at']) ? "" : ( new Carbon($data['uploaded_at']) )->format('m/d');
                }

                if( isset($data['checked_by']) ){
                    $checked_by_user = User::find($data['checked_by']);
                    $taskDetails['pre_live'][$option]['checked_by'] = is_null($checked_by_user) ? "" : $checked_by_user->name;
                    $taskDetails['pre_live'][$option]['checked_at'] = is_null($data['checked_at']) ? "" : ( new Carbon($data['checked_at']) )->format('m/d');
                }
            }
        }
        if( !is_null($task->client()) ){
            $taskDetails['client_name'] = $task->client()->name;
            $taskDetails['websites'] = $task->client()->websites()->orderBy('website')->get()->toArray();

            if( !is_null($task->website()) ){
                $taskDetails['website_url'] = $task->website()->website;
                $taskDetails['client_drive'] = $task->website()->drive;
            }
            else{
                $taskDetails['website_url'] = "";
            }
        }
        else {
            $taskDetails['client_name'] = "";
            $taskDetails['client_drive'] = "";
            $taskDetails['websites'] = [];
        }

        return response()->json([
            'status'    => 'success',
            'data'      => $taskDetails
        ]);
    }

    public function uploadFiles(Request $request)
    {
        $task = Task::find($request->input('taskId'));

        if( is_null($task) ){
            return response()->json([
                'status'    => 'error'
            ]);
        }

        $files = request()->file('taskFiles');
        $filesData = [];
        foreach( $files as $file )
        {
            $filePath = $file->store('task-files');
            $taskFile = new TaskFile([
                'task_id'       => $task->id,
                'origin_name'   => $file->getClientOriginalName(),
                'path'          => $filePath,
            ]);
            $taskFile->save();

            $filesData[] = $taskFile->toArray();
        }

        return response()->json([
            'status'    => 'success',
            'files'     => $filesData
        ]);
    }

    public function removeFile(Request $request)
    {
        $taskFile = TaskFile::find($request->input('taskFileId'));
        if( is_null($taskFile) )
            return response()->json([
                'status'    => 'error'
            ]);

        $taskFile->delete();
        return response()->json([
            'status'    => 'success'
        ]);
    }

    public function downloadFile(Request $request)
    {
        $taskFile = TaskFile::find($request->input('taskFileId'));
        if( is_null($taskFile) )
            return response()->json([
                'status'    => 'error'
            ]);
        return response()->json([
            'status'        => 'success',
            'downloadData'  => [
                'type'  => 'single',
                'public_url'   => Storage::url($taskFile->path),
                'file_name'     => $taskFile->origin_name
            ]
        ]);
    }

    public function deleteTask(Request $request)
    {
        $task = Task::find($request->input('taskId'));

        if( is_null($task) ){
            return response()->json([
                'status'    => 'error'
            ]);
        }

        $task->delete();
        return response()->json([
            'status'    => 'success'
        ]);
    }

    public function completeTask(Request $request)
    {
        $task = Task::find($request->input('taskId'));

        if( is_null($task) ){
            return response()->json([
                'status'    => 'error'
            ]);
        }

        $task->stage_id = 10;
        $task->completed = 1;
        $task->completed_at = $request->input('completedAt');
        $task->save();
        return response()->json([
            'status'    => 'success'
        ]);
    }

    public function updatePrePostOptions(Request $request)
    {
        $task = Task::find($request->input('taskId'));

        if( is_null($task) ){
            return response()->json([
                'status'    => 'error'
            ]);
        }

        $option = $request->input('option');
        $value = $request->input('value');

        if( $value == 'on' ) {
            $data = is_array($task->pre_live) ? $task->pre_live : [];

            if( !isset($data[$option]) )
                $data[$option] = [];

            $data[$option]['checked_by'] = Auth::user()->id;
            $data[$option]['checked_at'] = Carbon::now();
            $task->pre_live = $data;
            $task->save();
        }

        if( $value == 'off' ) {
            $data = $task->pre_live;
            unset($data[$option]['checked_by']);
            unset($data[$option]['checked_at']);
            if( !isset($data[$option]['uploaded_by']) )
                unset($data[$option]);
            $task->pre_live = $data;
            $task->save();
        }

        return response()->json([
            'status'        => 'success',
            'checked_by'    => Auth::user()->name,
            'checked_at'    => Carbon::now()->format('m/d')
        ]);
    }

    public function uploadPreImage(Request $request)
    {
        $task = Task::find($request->input('taskId'));
        $option = $request->input('option');

        if( is_null($task) || !$option ){
            return response()->json([
                'status'    => 'error'
            ]);
        }

        if ( !$request->hasFile('file') ) {     //check if file exist
            return response()->json([
                'status'    => 'error'
            ]);
        }

        $preLiveOptions = is_array($task->pre_live) ? $task->pre_live : [];

        $preLiveOptions[$option] = [
            'uploaded_by'                   => Auth::user()->id,
            'uploaded_at'                   => Carbon::now(),
            'uploaded_image'                => request()->file('file')->store('task-pre-images'),
            'uploaded_image_origin_name'    => request()->file('file')->getClientOriginalName()
        ];

        $task->pre_live = $preLiveOptions;
        $task->save();

        return response()->json([
            'status'        => 'success',
            'uploaded_by'   => Auth::user()->name,
            'uploaded_at'   => (new Carbon($preLiveOptions[$option]['uploaded_at']))->format('m/d')
        ]);
    }

    public function downloadPreImage(Request $request){
        $task = Task::find($request->input('taskId'));
        $option = $request->input('option');

        if( is_null($task) || is_null($option) || !isset($task->pre_live[$option]['uploaded_image']) )
            return response()->json([
                'status'    => 'error'
            ]);

        return response()->json([
            'status'        => 'success',
            'downloadData'  => [
                'type'  => 'single',
                'public_url'   => Storage::url($task->pre_live[$option]['uploaded_image']),
                'file_name'     => $task->pre_live[$option]['uploaded_image_origin_name']
            ]
        ]);
    }

    public function createComment(Request $request)
    {
        if( !is_null($request->input('commentId')) && $request->input('commentId') != -1 ) { //update existing comment
            $comment = Comment::find($request->input('commentId'));
            $comment->content = $request->input('content');
            event(new CommentCreatedEvent($comment->id, $comment->task_id, 1));
        }
        else {  //create new comment
            $comment = new Comment([
                'task_id'       => $request->input('taskId'),
                'author_id'     => Auth::user()->id,
                'content'       => $request->input('content')
            ]);
            event(new CommentCreatedEvent($comment->id, $comment->task_id));
        }
        $comment->save();

        return response()->json([
            'status'    => 'success'
        ]);
    }

    /**
     * Sync Task Comments
     *
     */
    public function syncTaskComments(Request $request)
    {
        $task = Task::find($request->input('taskId'));
        if( is_null($task) )
            return response()->json([
                'status'    => 'error'
            ]);
        $lastCommentId = $request->input('lastCommentId');

        $unSyncedComments = $task->comments()->where('id', '>', $lastCommentId)->orderBy('id')->get();
        $prettyComments = [];
        foreach( $unSyncedComments as $comment ){
            $prettyComment = $comment->toArray();

            $prettyComment['author_name'] = $comment->author()->name;
            $prettyComment['pretty_created_at'] = (new Carbon($comment->created_at))->format('m/d h:i a');
            $prettyComment['author_avatar'] = ($comment->author()->avatar == "" || is_null($comment->author()->avatar)) ? false : $comment->author()->getPublicAvatarLink();
            $prettyComment['author_initials'] = $comment->author()->getInitials();

            if( $comment->type == 'file' ) {
                if( $this->is_file_image($comment->file_origin_name) ){ //and also image
                    $prettyComment['file_type'] = 'image';
                    $prettyComment['image_public_link'] = Storage::url($comment->file_path);
                }
            }

            $prettyComments[] = $prettyComment;
        }

        return response()->json([
            'status'                => 'success',
            'comments'              => $prettyComments
        ]);
    }

    /**
     * Get Comment Data
     */
    public function getComment(Request $request)
    {
        $comment = Comment::find($request->input('commentId'));
        if( is_null($comment) )
            return response()->json([
                'status'    => 'error'
            ]);
        return response()->json([
            'status'    => 'success',
            'comment'   => $comment->toArray()
        ]);
    }

    /**
     * Remove Comment
     */
    public function removeComment(Request $request)
    {
        $comment = Comment::find($request->input('commentId'));
        if( is_null($comment) )
            return response()->json([
                'status'    => 'error'
            ]);

        event(new CommentRemovedEvent($comment->id, $comment->task_id));

        $comment->delete();

        return response()->json([
            'status'    => 'success'
        ]);
    }

    /**
     * Get Total comments count for Task
     */
    public function getCommentsCount(Request $request)
    {
        $task = Task::find($request->input('taskId'));
        if( is_null($task) )
            return response()->json([
                'status'    => 'error'
            ]);
        return response()->json([
            'status'        => 'success',
            'commentsCount' => count($task->comments()->get())
        ]);
    }

    /**
     * Task Pin Comment
     */
    public function pinComment(Request $request)
    {
        $comment = Comment::find($request->input('commentId'));
        if( is_null($comment) )
            return response()->json([
                'status'    => 'error'
            ]);
        if( $request->input('status') == 'on' ) {
            $comment->pin = true;
            event(new CommentPinEvent($comment->id, $comment->task_id, true));

        }
        else {
            $comment->pin = false;
            event(new CommentPinEvent($comment->id, $comment->task_id, false));
        }
        $comment->save();

        return response()->json([
            'status'    => 'success'
        ]);
    }

    /**
     * Upload Files for Comment
     */
    public function uploadCommentFiles(Request $request)
    {

        $files = request()->file('files');
        $filesPath = [];
        foreach( $files as $file )
        {
            $comment = new Comment([
                'task_id'           => $request->input('taskId'),
                'author_id'         => Auth::user()->id,
                'type'              => 'file',
                'file_path'         => $file->store('comment-attachments'),
                'file_origin_name'  => $file->getClientOriginalName(),
            ]);
            event(new CommentCreatedEvent($comment->id, $comment->task_id));
            $comment->save();
        }

        return response()->json([
            'status'    => 'success'
        ]);
    }

    /**
     * Download Comment File
     */
    public function downloadCommentFile(Request $request)
    {
        $comment = Comment::find($request->input('commentId'));

        if( is_null($comment) )
            abort(404);

        return response()->json([
            'status'        => 'success',
            'downloadData'  => [
                'type'  => 'single',
                'public_url'   => Storage::url($comment->file_path),
                'file_name'     => $comment->file_origin_name
            ]
        ]);
    }

    /**
     * check if file name is image
     */
    public function is_file_image($filename)
    {
        $regex = '/\.(jpe?g|bmp|png|JPE?G|BMP|PNG)(?:[\?\#].*)?$/';

        return preg_match($regex, $filename);
    }
}
