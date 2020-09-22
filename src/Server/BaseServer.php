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

    protected const SW_SOCK_TCP6 = SWOOLE_SOCK_TCP6;    //tcp ipv6 socket
    protected const SW_SOCK_UDP = SWOOLE_SOCK_UDP;  //udp ipv4 socket
    protected const SW_SOCK_UDP6 = SWOOLE_SOCK_UDP6;    //udp ipv6 socket
    protected const SW_SOCK_TCP = SWOOLE_SOCK_TCP;  //tcp ipv4 socket
    protected const SW_SOCK_UNIX_STREAM = SWOOLE_UNIX_STREAM;
    protected const SW_SOCK_UNIX_DGRAM = SWOOLE_SOCK_UNIX_DGRAM;
    protected const SW_SOCK_HTTP = 7;

    /**
     * @var array
     * @see https://wiki.swoole.com/#/server/setting?id=reactor_num
     */
    protected $defaultSettle = [
        'reactor_num' => 1,    //线程数 默认会启用 CPU 核数相同的数量  建议设置为 CPU 核数的 1-4 倍
        'worker_num' => 1, //设置启动的 Worker 进程数  【默认值：CPU 核数】
        'max_request' => 100,   //设置 worker 进程的最大任务数。【默认值：0 即不会退出进程】达到 max_request 不一定马上关闭进程 SWOOLE_BASE 下，达到 max_request 重启进程会导致客户端连接断开
        'max_conn' => 100,  //最大允许的连接数 默认 ulimit -n
        'task_worker_num' => 2, //配置 Task 进程的数量。【默认值：未配置则不启动 task】 不超过swoole_cpu_num() * 100
        'task_ipc_mode' => 1,   //设置 Task 进程与 Worker 进程之间通信的方式。【默认值：1】
        'task_max_request' => 0,    //设置 task 进程的最大任务数。【默认值：0】
        'task_tmpdir' => '/tmp',    //设置 task 的数据临时目录。【默认值：Linux /tmp 目录】
        'task_enable_coroutine' => false,   //开启 Task 协程支持。【默认值：false】
        'task_use_object' => false, //使用面向对象风格的 Task 回调格式
        'dispatch_mode' => 2,   //数据包分发策略
        //'dispatch_func' => '',  //设置 dispatch 函数，Swoole 底层内置了 6 种 dispatch_mode，如果仍然无法满足需求。可以使用编写 C++ 函数或 PHP 函数，实现 dispatch 逻辑。
        'message_queue_key' => 'ftok($php_script_file, 1)', //设置消息队列的 KEY。【默认值：ftok($php_script_file, 1)】
        'daemonize' => false,   //守护进程化【默认值：0】
        'backlog' => 128,   //设置 Listen 队列长度
        'log_file' => '/tmp/swoole.log',    //指定 Swoole 错误日志文件
        'log_level' => SWOOLE_LOG_DEBUG,    //错误日志打印的等级，范围是 0-6。低于 log_level 设置的日志信息不会抛出。【默认值：SWOOLE_LOG_DEBUG】
        'open_tcp_keepalive' => 0,  //检测死连接
        'heartbeat_check_interval' => true, //启用心跳检测【默认值：false】
        'heartbeat_idle_time' => 600,   //连接最大允许空闲的时间
        'open_eof_check' => false,   //打开 EOF 检测【默认值：false】
        'open_eof_split' => false,  //启用 EOF 自动分包
        'package_eof' => '\n\n',    //设置 EOF 字符串
        'open_length_check' => false,   //打开包长检测特性【默认值：false】
        'package_length_type' => 'l',    //长度值的类型，接受一个字符参数
        //'package_length_func' => '',    //设置长度解析函数
        'package_max_length' => 2 * 1024 * 1024,    //设置最大数据包尺寸，单位为字节
        'open_http_protocol' => false,  //启用 HTTP 协议处理。【默认值：false】
        'open_mqtt_protocol' => false,  //启用 MQTT 协议处理。【默认值：false】
        'open_websocket_protocol' => false, //启用 WebSocket 协议处理。【默认值：false】
        'open_websocket_close_frame' => false,  //启用 websocket 协议中关闭帧。【默认值：false】
        'open_tcp_nodelay' => false,    //启用 open_tcp_nodelay。【默认值：false】
        'open_cpu_affinity' => false,   //启用 CPU 亲和性设置。 【默认 false 关闭】
        'cpu_affinity_ignore' => [],    //IO 密集型程序中，所有网络中断都是用 CPU0 来处理，如果网络 IO 很重，CPU0 负载过高会导致网络中断无法及时处理，那网络收发包的能力就会下降。
        'tcp_defer_accept' => false,    //启用 tcp_defer_accept 特性【默认值：false 关闭】
        'ssl_cert_file' => '',  //设置 SSL 隧道加密。【默认值：无】
        'ssl_method' => SWOOLE_SSLv23_METHOD,   //设置 OpenSSL 隧道加密的算法
        'ssl_protocols' => 0,   //设置 OpenSSL 隧道加密的协议
        'ssl_ciphers' => 'EECDH+AESGCM:EDH+AESGCM:AES256+EECDH:AES256+EDH', //设置 openssl 加密算法
        'ssl_verify_peer' => false, //服务 SSL 设置验证对端证书。【默认值：false】
        'user' => '',   //设置 Worker/TaskWorker 子进程的所属用户
        'group' => '',  //设置 Worker/TaskWorker 子进程的进程用户组。【默认值：执行脚本用户组】
        'chroot' => '', //重定向 Worker 进程的文件系统根目录。【默认值：无】
        'pid_file' => '',   //pid文件地址
        'buffer_output_size' => 32 * 1024 * 1024,   //配置发送输出缓存区内存尺寸。【默认值：2M】
        'socket_buffer_size' => 128 * 1024 *1024,   //配置客户端连接的缓存区长度。【默认值：2M】
        'enable_unsafe_event' => false, //启用 onConnect/onClose 事件
        'discard_timeout_request' => true,  //丢弃已关闭链接的数据请求。【默认值：true】
        'enable_reuse_port' => false,   //设置端口重用。【默认值：false】
        'enable_delay_receive' => false,    //设置 accept 客户端连接后将不会自动加入 EventLoop
        'reload_async' => true, //设置异步重启开关。【默认值：true】
        'max_wait_time' => 3,   //设置 Worker 进程收到停止服务通知后最大等待时间【默认值：3】
        'tcp_fastopen' => false,    //开启 TCP 快速握手特性。【默认值：false】
        'request_slowlog_file' => false,    //开启请求慢日志。【默认值：false】
        'enable_coroutine' => 'On', //开启异步风格服务器的协程支持【默认值：On】
        'max_coroutine' => 3000,    //设置当前工作进程最大协程数量。【默认值：3000】
        'send_yield' => true,   //当发送数据时缓冲区内存不足时，直接在当前协程内 yield，等待数据发送完成，缓存区清空时，自动 resume 当前协程，继续 send 数据。【默认值：在 dispatch_mod 2/4 时候可用，并默认开启】
        'send_timeout' => 0,    //设置发送超时，与 send_yield 配合使用，当在规定的时间内，数据未能发送到缓存区，底层返回 false，并设置错误码为 ETIMEDOUT，可以使用 getLastError() 方法获取错误码。
        'hook_flags' => SWOOLE_HOOK_SLEEP   //设置一键协程化 Hook 的函数范围。【默认值：不 hook】
    ];

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
    protected $server;

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
            $this->server = new \Swoole\Http\Server(
                $this->host,
                $this->port,
                $this->mode
            );
        }else{
            $this->server = new Server(
                $this->host,
                $this->port,
                $this->mode,
                $sockType
            );
        }
    }

    /**
     * @param array $settle
     */
    public function set(array $settle){
        $this->server->set(array_merge($this->defaultSettle, $settle));
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
     */
    public function start(){
        foreach ($this->callbacks as $event => $callback){
            $this->server->on($event, $callback);
        }
        $this->server->start();
    }

    /**
     * @return Server
     */
    public function getServer(){
        return $this->server;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->server, $name], $arguments);
    }
}