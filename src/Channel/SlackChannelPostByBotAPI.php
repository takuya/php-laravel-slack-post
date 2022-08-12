<?php

namespace Takuya\PhpLaravelSlackPost\Channel;


use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;
use Takuya\PhpLaravelSlackPost\SlackAPI\SlackPostMessageAPI;

class SlackChannelPostByBotAPI implements CustomChannelContract {
  
  
  public function send( $notifiable, Notification $notification ) {
    
    
    $formatter = 'toSlack';
    
    if ( !method_exists($notification,$formatter)){
      throw new \RuntimeException('toSlack not found in $notifiable');
    }
    //
    $token = null;
    $token = $token ?: config( 'slack.token' );
    $token = $token ?: env('SLACK_TOKEN');
    $cli = new SlackPostMessageAPI($token);
    /** @var SlackMessage $message */
    $message = $notification->{$formatter}( $notifiable );
    $cli->setMessage( $message );
    $cli->send();
  }
}