<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/9/22
 * Time: 10:21
 */
require __DIR__ .'/../bootstrap.php';

use Core\Server\TcpServer;
use Swoole\Server;
use Core\Http\HttpRequest;
use Core\Http\HttpResponse;

$tcpServer = new TcpServer(
    '0.0.0.0',
    8082,
    SWOOLE_PROCESS
);

$tcpServer->on('start', function (Server $server){
    println("run at http:{$server->host}:{$server->port}");
});

$tcpServer->on('receive', function(Server $server, $fd, $from_id, $data) {
    $httpRequest = new HttpRequest($data);
    $httpResponse = new HttpResponse('connection id '.$fd);
    $server->send($fd, $httpResponse->getContent());
    $server->exists($fd) && $server->close($fd);
});

$tcpServer->on('task', function (){

});

$tcpServer->start();