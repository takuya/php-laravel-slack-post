<?php

namespace Takuya\PhpLaravelSlackPost\Channel;

use Illuminate\Notifications\Notification;

interface CustomChannelContract {
  
  public function send($notifiable, Notification $notification);
  
}