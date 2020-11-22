<?php
namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotificationStatusChangedEvent implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  //public $notification_count;

  public function __construct()
  {
    //list($notifications, $actionNotifications) = get_global_notifications();
    //$this->notification_count = count($actionNotifications);
  }

  public function broadcastOn()
  {

    return ['notification'];
  }

  public function broadcastAs()
  {
      return 'notification-status-changed';
  }
}
