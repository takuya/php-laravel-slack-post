<?php

namespace Tests\Feature;

use Tests\TestCase;
use Takuya\PhpLaravelSlackPost\SlackAPI\SlackReadMessageAPI;
use Takuya\PhpLaravelSlackPost\SlackAPI\SlackPostMessageAPI;

class SlackPostTest extends TestCase {
  
  public function testPostSlackChannel () {
    $token =getenv('SLACK_TOKEN');
    $channel =[
      'name'=>getenv('SLACK_CHANNEL_NAME'),
      'id'=>getenv('SLACK_CHANNEL_ID')
    ];
    $message ='@takuya テスト・メッセージ / '.time();
    //
    $cli = new SlackPostMessageAPI($token);
    $cli->content($message)
        ->to($channel['name'])
        ->send();
    //
    $cli = new SlackReadMessageAPI($token);
    $ret = $cli->latestMessage($channel['id']);
    $this->assertEquals($message,$ret->text);
  }
}