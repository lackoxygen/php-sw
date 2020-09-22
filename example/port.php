<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/9/22
 * Time: 14:05
 */
require __DIR__ .'/../bootstrap.php';

use Swoole\Client;

$ip = '127.0.0.1';

$ports = [];

for ($port = 1; $port <= 65535; $port ++){
    $ports[] = $port;
}

$chunks = array_chunk($ports, 2000);

$pool = new Swoole\Process\Pool(count($chunks));

$pool->on('WorkerStart', function (Swoole\Process\Pool $pool, $workerId) use($chunks, $ip){
    foreach ($chunks[$workerId] as $port){
        $client = new Client(SWOOLE_TCP, false);
        @$client->connect($ip, $port);
        if ($client->isConnected()){
            $client->close();
            println("ping {$ip}:{$port} opened");
        }
    }
    $pool->shutdown();
});

$pool->start();