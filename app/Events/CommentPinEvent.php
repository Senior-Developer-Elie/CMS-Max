<?php
namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommentPinEvent implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $comment_id;
  public $task_id;
  public $status;

  public function __construct($comment_id, $task_id, $status)
  {
      $this->comment_id = $comment_id;
      $this->task_id = $task_id;
      $this->status = $status;
  }

  public function broadcastOn()
  {

    return ['task-comment'];
  }

  public function broadcastAs()
  {
      return 'comment-pin';
  }
}
