<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/9/21
 * Time: 17:17
 */
require __DIR__ .'/../bootstrap.php';

use Core\Server\UdpServer;

$udpServer = new UdpServer('0.0.0.0', 8081);

$udpServer->on('start', function (Swoole\Server $server){
    println("run at udp:{$server->host}:{$server->port}");
});

$udpServer->on('packet', function (Swoole\Server $server, string $data, array $client){
    println("packet from {$client['address']}:{$client['port']} -> {$data}");

    $connect = new Swoole\Coroutine\Client(SWOOLE_SOCK_UDP);

    if(!$connect->connect($client['address'], $client['port'])){
        println('connect fail');
        return;
    }

    $connect->send($data);
});

$udpServer->start();


