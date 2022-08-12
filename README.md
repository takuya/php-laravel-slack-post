# php-laravel-slack-post
slack post by bot account as laravel plugin


## Installing

```sh
composer config repositories.'php-laravel-slack-post' \
         vcs https://github.com/takuya/php-laravel-slack-post  
composer require takuya/php-laravel-slack-post:master
composer install 
```
## Using 
```sh
php artisan make:notification SampleNotify
php artisan make:command SlaclNotifySample
```
#### app/Notification/SampleNotify.php
```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Takuya\PhpLaravelSlackPost\SlackWorkSpaceChannel;
use Illuminate\Notifications\Messages\SlackMessage;
use Takuya\PhpLaravelSlackPost\Channel\SlackChannelPostByBotAPI;

class SampleNotify extends Notification {
  use Queueable;
  
  protected $username = 'サンプル通知';
  public function via ( $notifiable ) {
    return [SlackChannelPostByBotAPI::class];
  }
  
  /**
   * @param SlackWorkSpaceChannel $notifiable
   * @return \Illuminate\Notifications\Messages\SlackMessage
   */
  public function toSlack ( SlackWorkSpaceChannel $notifiable ): SlackMessage {
    $message = new SlackMessage();
    $message->from( $this->username )
            ->to( $notifiable->getChannelName() )
            ->content('Hello World');
    return $message;
  }
}
```
### app/Commands/SlackNotifySample.php

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Takuya\PhpLaravelSlackPost\SlackWorkSpaceChannel;
use App\Notifications\SampleNotify;

class SlaclNotifySample extends Command {
  protected $signature = 'slack:notify';
  
  public function handle () {
    $slack = new SlackWorkSpaceChannel(config('slack.channel_id'));
    $slack->notify( new SampleNotify() );
  }
}
```
### .env
```
SLACK_TOKEN=xoxb-12xxxxxxxxxxxxxx---xxx
SLACK_CHANNEL_NAME=通知テスト用
SLACK_CHANNEL_ID=C01AXXXXX
```
### config/slack.php
```php
<?php

return [
  'token'=>env('SLACK_TOKEN'),
  'channel_id'=>env('SLACK_CHANNEL_NAME'),
  'channel_name'=>env('SLACK_CHANNEL_ID'),
];
```
### run
```sh
php artisan slack:notify
```

### result 

![](https://user-images.githubusercontent.com/55338/184298814-74c1e7dd-46e0-407b-9722-a29e93eb093b.png)

## testing 
```sh
## prepare
git clone https://github.com/takuya/php-laravel-slack-post
cd php-laravel-slack-post
composer install
## test
export SLACK_TOKEN=xoxb-XXXXX-XXXXXXXXXQoOS5sOv
export SLACK_CHANNEL_NAME='#通知テスト'
export SLACK_CHANNEL_ID=C01AXXXXX
vendor/bin/phpunit --filter testPostSlackChannel     
```


