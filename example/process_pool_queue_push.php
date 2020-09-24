<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/9/23
 * Time: 9:26
 */

require __DIR__ .'/../bootstrap.php';

$msgQueueKey = $argv[1] ?? 110;

if (!function_exists('msg_get_queue')){
    throw new \Exception('./configure require --enable-sysvmsg');
}
/**
 * @var $queue resource
 */
$queue = msg_get_queue($msgQueueKey);

$i = 0;
while (true){
    msg_send($queue, 1, $i, false);
    println("push {$i} -> {$queue}");
    sleep(1);
    $i ++;
}