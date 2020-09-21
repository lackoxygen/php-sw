<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/9/21
 * Time: 17:15
 */
namespace Core\Server;

/**
 * Class TcpServer
 * @package Core\Server
 */
class TcpServer extends BaseServer{

    /**
     * TcpServer constructor.
     * @param string $host
     * @param int $port
     * @param int $mode
     */
    public function __construct(string $host, int $port, int $mode = SWOOLE_BASE)
    {
        parent::__construct($host, $port, $mode, self::SW_SOCK_TCP);
    }
}