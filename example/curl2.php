<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/9/24
 * Time: 13:57
 */
require __DIR__ .'/../bootstrap.php';

use Core\HttpClient\Curl;
use Swoole\Coroutine;
use Swoole\Runtime;
use Swoole\Coroutine\Channel;

Coroutine::create(function(){
    Runtime::setHookFlags(SWOOLE_HOOK_CURL);
    $curl = new Curl('http://127.0.0.1:8080');
    $channel = new Channel();
    for ($i = 0; $i < 100; $i ++){
        Coroutine::create(function() use($curl, $channel){
            $response = $curl
                ->withHeader('token', '123456')
                ->withCookie('session', '1')
                ->get('/', ['sleep' => 1]);
            $channel->push($response);
        });
    }

    for ($i = 0; $i < 100; $i ++){
        println($channel->pop()->body);
    }
    $channel->close();
});
/**
 * -------------------
 *  开启curl_hook
 *  real	0m1.057s
 *  user	0m0.015s
 *  sys	    0m0.024s
 * -------------------
 *  关闭curl_hook
 *  real	1m40.336s
 *  user	0m0.035s
 *  sys	0m0.038s
 * -------------------
 */