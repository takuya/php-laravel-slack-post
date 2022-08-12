<?php

namespace Takuya\PhpLaravelSlackPost\SlackAPI;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class SlackReadMessageAPI {
  
  /**
   * @var string
   */
  protected $endpoint = 'https://slack.com/api';
  
  /**
   * @param $token string Slack Bot Token.
   */
  public function __construct ( $token ) {
    $this->token = $token;
  }
  
  /**
   * @param $channel_id
   * @return mixed
   * @throws \Exception
   */
  public function latestMessage ( $channel_id ) {
    $ret = $this->readChannel( $channel_id, 1, 0 );
    return $ret[0];
  }
  
  /**
   * @param $channel_id
   * @param $limit
   * @param $latest
   * @return array
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function readChannel ( $channel_id, $limit = 10, $latest = '0' ): array {
    $url = $this->endpoint.'/conversations.history';
    try {
      $cli = new Client();
      $res = $cli->request( "POST",
        $url,
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