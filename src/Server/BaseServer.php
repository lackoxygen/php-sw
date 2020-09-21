<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/9/21
 * Time: 15:42
 */
namespace Core\Server;

use Swoole\Server;

/**
 * Class BaseServer
 * @package Core\Server
 */
abstract class BaseServer{
    protected const SW_SOCK_TCP6 = SWOOLE_SOCK_TCP6;
    protected const SW_SOCK_UNIX_STREAM = SWOOLE_UNIX_STREAM;
    protected const SW_SOCK_UDP = SWOOLE_SOCK_UDP;
    protected const SW_SOCK_UNIX_DGRAM = SWOOLE_SOCK_UNIX_DGRAM;
    protected const SW_SOCK_TCP = SWOOLE_SOCK_TCP;
    protected const SW_SOCK_HTTP = 7;

    protected $events = [
        'start',
        'shutdown',
        'workerStart',
        'workerStop',
        'workerExit',
        'connect',
        'receive',
        'packet',
        'close',
        'task',
        'finish',
        'pipeMessage',
        'workerError',
        'managerStart',
        'managerStop',
        'beforeReload',
        'afterReload',
        'request'
    ];

    /**
     * @var Server
     */
    protected $swServer;

    /**
     * @var int
     */
    protected $sockType;

    /**
     * @var string
     */
    protected $host = '127.0.0.1';

    /**
     * @var int
     */
    protected $port = 8080;

    /**
     *  swoole_server.cc
     *  static unordered_map<string, ServerEvent> server_event_map({
     *  { "start",        ServerEvent(SW_SERVER_CB_onStart,        "Start") },
     *  { "shutdown",     ServerEvent(SW_SERVER_CB_onShutdown,     "Shutdown") },
     *  { "workerstart",  ServerEvent(SW_SERVER_CB_onWorkerStart,  "WorkerStart") },
     *  { "workerstop",   ServerEvent(SW_SERVER_CB_onWorkerStop,   "WorkerStop") },
     *  { "beforereload",  ServerEvent(SW_SERVER_CB_onBeforeReload,  "BeforeReload") },
     *  { "afterreload",  ServerEvent(SW_SERVER_CB_onAfterReload,  "AfterReload") },
     *  { "task",         ServerEvent(SW_SERVER_CB_onTask,         "Task") },
     *  { "finish",       ServerEvent(SW_SERVER_CB_onFinish,       "Finish") },
     *  { "workerexit",   ServerEvent(SW_SERVER_CB_onWorkerExit,   "WorkerExit") },
     *  { "workererror",  ServerEvent(SW_SERVER_CB_onWorkerError,  "WorkerError") },
     *  { "managerstart", ServerEvent(SW_SERVER_CB_onManagerStart, "ManagerStart") },
     *  { "managerstop",  ServerEvent(SW_SERVER_CB_onManagerStop,  "ManagerStop") },
     *  { "pipemessage",  ServerEvent(SW_SERVER_CB_onPipeMessage,  "PipeMessage") },
     *  });
     * @var array
     */

    protected $callbacks = [];

    /**
     * SWOOLE_BASE | SWOOLE_PROCESS
     * @var int
     */
    protected $mode = SWOOLE_BASE;

    /**
     * BaseServer constructor.
     * @param string $host
     * @param int $port
     * @param int $mode
     * @param int $sockType
     */
    public function __construct(string $host, int $port, int $mode = SWOOLE_BASE, int $sockType)
    {
        $this->host = $host;
        $this->port = $port;
        $this->mode = $mode;
        if ($sockType === self::SW_SOCK_HTTP){
            $this->swServer = new \Swoole\Http\Server(
                $this->host,
                $this->port,
                $this->mode
            );
        }else{
            $this->swServer = new Server(
                $this->host,
                $this->port,
                $this->mode,
                $sockType
            );
        }
    }


    /**
     * @param string $event
     * @param callable $callback
     * @param bool $force
     * @return $this
     */
    public function on(string $event, callable $callback, $force = true){
        if ($force === true){
            $this->callbacks[$event] = $callback;
        }else{
            !isset($this->callbacks[$event]) && $this->callbacks[$event] = $callback;
        }
        return $this;
    }

    /**
     * start server
     * @return mixed
     */
    public function start(){
        foreach ($this->callbacks as $event => $callback){
            $this->swServer->on($event, $callback);
        }
        return $this->swServer->start();
    }
}