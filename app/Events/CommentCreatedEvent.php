<?php
namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommentCreatedEvent implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $comment_id;
  public $task_id;
  public $is_update;

  public function __construct($comment_id, $task_id, $is_update = 0)
  {
      $this->comment_id = $comment_id;
      $this->task_id = $task_id;
      $this->is_update = $is_update;
  }

  public function broadcastOn()
  {

    return ['task-comment'];
  }

  public function broadcastAs()
  {
      return 'comment-created';
  }
}
