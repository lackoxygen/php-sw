<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/9/21
 * Time: 14:53
 */
namespace Core\Server;

/**
 * Class HttpServer
 * @package Core\Server
 */
class HttpServer extends BaseServer {


    /**
     * HttpServer constructor.
     * @param string $host
     * @param int $port
     * @param int $mode
     */
    public function __construct(string $host, int $port, int $mode = SWOOLE_BASE)
    {
        parent::__construct($host, $port, $mode, self::SW_SOCK_HTTP);
    }
}