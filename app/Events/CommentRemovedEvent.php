<?php
namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommentRemovedEvent implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $comment_id;
  public $task_id;

  public function __construct($comment_id, $task_id)
  {
      $this->comment_id = $comment_id;
      $this->task_id = $task_id;
  }

  public function broadcastOn()
  {

    return ['task-comment'];
  }

  public function broadcastAs()
  {
      return 'comment-removed';
  }
}
