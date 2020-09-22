<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/9/22
 * Time: 16:57
 */
require __DIR__ .'/../bootstrap.php';

$pool = new \Swoole\Process\Pool(2);

$pool->set(['enable_coroutine' => false]);

$pool->on('WorkerStart', function (\Swoole\Process\Pool $pool, $workerId){
    $begTime = time();
    do{
        println("#{$workerId} exec");
    }while(time() - $begTime <= 5);
    println("process {$workerId} exit");
    $pool->shutdown();
});

$pool->on('WorkerStop', function (\Swoole\Process\Pool $pool, $workerId){
    println("stop");
});

$pool->on('Start', function (Swoole\Process\Pool $pool){
   println("pool start");
});

$pool->start();