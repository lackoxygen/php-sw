<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/9/24
 * Time: 10:39
 */
require __DIR__ .'/../bootstrap.php';

use Core\HttpClient\Curl;

$curl = new Curl('http://127.0.0.1:8080');

for ($i = 0; $i < 100; $i ++){
    $response = $curl
        ->withHeader('token', '123456')
        ->withCookie('session', '1')
        ->get('/', ['sleep' => 1]);
    println($response->body);
}
/**
 * ----------------------------------
 *  real	1m40.394s
 *  user	0m0.049s
 *  sys	    0m0.035s
 * ----------------------------------
 */

