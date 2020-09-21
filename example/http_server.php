<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/9/21
 * Time: 14:52
 */
require __DIR__ .'/../bootstrap.php';

use Core\Server\HttpServer;
use Swoole\Http\Request;
use Swoole\Http\Response;

$httpServer = new HttpServer(
    '0.0.0.0',
    8080
);

$httpServer->on('request', function (Request $request, Response $response){
    $response->write("connect id "); //write 就不要用end
    $response->write($response->fd);
});

$httpServer->on('start', function (Swoole\Http\Server $server){
    println("run at http:{$server->host}:{$server->port}");
});

$httpServer->on('workerStart', function (Swoole\Http\Server $server, $pid){
    println("worker pid #{$pid} start");
});

$httpServer->on('workerStop', function (Swoole\Http\Server $server, $pid){
    println("worker pid #{$pid} stop");
});

$httpServer->on('workerExit', function (Swoole\Http\Server $server, $pid){
    println("worker pid #{$pid} exit");
});

$httpServer->start();

