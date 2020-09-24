<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/9/24
 * Time: 15:16
 */
require __DIR__ .'/../bootstrap.php';

use Core\HttpClient\GuzzleClient;
use Swoole\Coroutine;
use Swoole\Runtime;
use Swoole\Coroutine\Channel;

Coroutine::create(function(){
    Runtime::setHookFlags(SWOOLE_HOOK_CURL);    //从 v4.5.4 版本起，SWOOLE_HOOK_ALL 包括 SWOOLE_HOOK_CURL
    $guzzleClient = new GuzzleClient(
        'http://127.0.0.1:8080'
    );
    $channel = new Channel();
    for ($i = 0; $i < 100; $i ++){
        Coroutine::create(function() use($guzzleClient, $channel){
            $channel->push($guzzleClient->get('/', ['sleep' => 1]));
        });
    }

    for ($i = 0; $i < 100; $i ++){
        $channel->pop();
    }

    $channel->close();
});

/**
 * -------------------
 *  real	0m1.131s
 *  user	0m0.023s
 *  sys	    0m0.056
 * -------------------
 */
