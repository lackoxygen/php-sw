<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/9/24
 * Time: 17:41
 */
require __DIR__ .'/../bootstrap.php';

use Swoole\{Coroutine, Runtime};

Runtime::enableCoroutine(true);

$begTime = microtime(true);

Coroutine::create(function (){
    for ($i = 0; $i < 100; $i ++){
        $pdo = new PDO(
            sprintf('%s:host=%s;dbname=%s', 'mysql', '127.0.0.1', 'laravel'),
            'root',
            'my-123456'
        );
        Coroutine::create(function() use($pdo){
            $pdo->query("select sleep(1)")
                ->fetchAll(PDO::FETCH_ASSOC);
        });
    }
});
Runtime::enableCoroutine(false);
println(microtime(true) - $begTime);
$begTime = microtime(true);
for ($i = 0; $i < 100; $i ++){
    $pdo = new PDO(
        sprintf('%s:host=%s;dbname=%s', 'mysql', '127.0.0.1', 'laravel'),
        'root',
        'my-123456'
    );
    $pdo->query("select sleep(1)")
        ->fetchAll(PDO::FETCH_ASSOC);

}

println(microtime(true) - $begTime);
