<?php

namespace Takuya\PhpLaravelSlackPost;

use Illuminate\Notifications\Notifiable;

class SlackWorkSpaceChannel {
  use Notifiable;
  
  /** @var string */
  protected $channel_name;
  
  /**
   * SlackRoom constructor.
   * @param $channelName_in_slack
   */
  public function __construct( $channelName_in_slack ) {
    $this->channel_name = $channelName_in_slack;
  }
  
  /**
   * @return string
   */
  public function getChannelName():string {
    return $this->channel_name;
  }
  
}