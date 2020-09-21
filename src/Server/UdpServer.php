<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/9/21
 * Time: 17:13
 */
namespace Core\Server;

/**
 * Class UdpServer
 * @package Core\Server
 */
class UdpServer extends BaseServer{
    /**
     * UdpServer constructor.
     * @param string $host
     * @param int $port
     * @param int $mode
     */
    public function __construct(string $host, int $port, int $mode = SWOOLE_BASE)
    {
        parent::__construct($host, $port, $mode, self::SW_SOCK_UDP);
    }
}