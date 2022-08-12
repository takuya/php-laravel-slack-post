<?php

namespace Takuya\PhpLaravelSlackPost\SlackAPI;

use Illuminate\Notifications\Messages\SlackMessage;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class SlackPostMessageAPI {
  
  /**
   * @var string
   */
  protected $endpoint = 'https://slack.com/api';
  /**
   * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
   */
  protected $token;
  /**
   * @var string
   */
  protected $channel;
  /**
   * @var string
   */
  protected $content;
  
  /** @var SlackMessage */
  protected $message;
  
  public function __construct ( $token ) {
    $this->token = $token;
  }
  
  public function content ( string $message ) {
    $this->content = $message;
    
    return $this;
  }
  
  public function to ( string $channel ) {
    $this->channel = $channel;
    
    return $this;
  }
  
  public function send () {
    $this->send_to_api();
    
    return $this;
  }
  
  protected function send_to_api () {
    $params = $this->jsonBuilder();
    try {
      $cli = new Client();
      $res = $cli->request(
        "POST",
        $this->endpoint.'/chat.postMessage',
        [
          'form_params' => $params,
          'allow_redirects' => false,
        ] );
      $ret = $res->getBody()->getContents();
      return true;
    } catch (ClientException $e) {
      return false;
    }
  }
  
  protected function jsonBuilder () {
    $params = [];
    if ( $this->message ) {
      $params = $this->message_to_paramters();
    }
    $params = array_merge( $params, array_filter( [
      'token' => $this->token,
      'channel' => $this->channel,
      'text' => $this->content,
    ] ) );
    return $params;
  }
  
  protected function message_to_paramters () {
    $message = $this->message;
    $optionalFields = array_filter(
      [
        'channel' => data_get( $message, 'channel' ),
        'icon_emoji' => data_get( $message, 'icon' ),
        'icon_url' => data_get( $message, 'image' ),
        'link_names' => data_get( $message, 'linkNames' ),
        'unfurl_links' => data_get( $message, 'unfurlLinks' ),
        'unfurl_media' => data_get( $message, 'unfurlMedia' ),
        'username' => data_get( $message, 'username' ),
      ] );
    
    return array_merge( [
      'text' => $message->content,
      //'attachments' => $this->attachments($message),
    ],
      $optionalFields );
  }
  
  public function setMessage ( SlackMessage $message ) {
    $this->message = $message;
  }
  
  public function latestMessage ( $ch ) {
    $ret = $this->readChannel( $ch, 1, 0 );
    return $ret[0];
  }
  
  public function readChannel ( $channel_id, $limit = 10, $latest = '0' ): array {
    try {
      $cli = new Client();
      $res = $cli->request( "POST",
        $this->endpoint.'/conversations.history',
        [
          'form_params' => [
            'token' => $this->token,
            'channel' => $channel_id,
            'limit' => $limit,
            'latest' => $latest,
          ],
          'allow_redirects' => false] );
      $content = $res->getBody()->getContents();
      $response = json_decode( $content );
      if ( !$response->ok ) {
        throw new \Exception( $content );
      }
      return $response->messages;
    } catch (ClientException $e) {
      return [];
    }
  }
}