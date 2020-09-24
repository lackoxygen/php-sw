<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/9/24
 * Time: 16:49
 */
require __DIR__ .'/../bootstrap.php';

use Core\Pool\ConnectionPool;



$pool = new \Core\Pool\RedisConnection(100);

$pool->init();
