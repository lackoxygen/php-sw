<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/9/22
 * Time: 16:57
 */
require __DIR__ .'/../bootstrap.php';

$msgQueueKey = 110;

$pool = new \Swoole\Process\Pool(2, SWOOLE_IPC_MSGQUEUE, $msgQueueKey);

$pool->on('WorkerStart', function (\Swoole\Process\Pool $pool, $workerId){
    println("#{$workerId} start");
});

$pool->on('WorkerStop', function (\Swoole\Process\Pool $pool, $workerId){
    println("#{$workerId} stop");
});

$pool->on('Message', function (\Swoole\Process\Pool $pool, $data){
    println("#recv sys -> {$data}");
});

$pool->start();