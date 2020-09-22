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

$tcpServer = new TcpServer(
    '0.0.0.0',
    8082,
    SWOOLE_PROCESS
);

$tcpServer->on('start', function (Server $server){
    println("run at http:{$server->host}:{$server->port}");
});

$tcpServer->on('receive', function(Server $server, $fd, $_, $data) {
    println("recv -> {$data}");
    $server->task($data);
});

$tcpServer->on('task', function (){

});


$tcpServer->set(
    [
        'dispatch_func' => function($server, $fd, $type, $data){
            println( "[$fd] $type >> $data");
        }
    ]
);

$tcpServer->addlistener('0.0.0.0', 8083, SWOOLE_SOCK_TCP);

println("add listener :8083");

$tcpServer->start();