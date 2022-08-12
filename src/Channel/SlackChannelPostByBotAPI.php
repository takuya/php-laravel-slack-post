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
    $cli = new SlackPostMessageAPI(config( 'slack.token' ));
    /** @var SlackMessage $message */
    $message = $notification->{$formatter}( $notifiable );
    $cli->setMessage( $message );
    $cli->send();
  }
}