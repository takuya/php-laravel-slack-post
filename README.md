# php-laravel-slack-post
slack post by bot account as laravel plugin


## Installing

```sh
composer config repositories.'php-laravel-slack-post' vcs https://github.com/takuya/php-laravel-slack-post  
composer require takuya/php-laravel-slack-post:master
composer install 
```

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


