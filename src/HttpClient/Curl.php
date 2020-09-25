<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/9/24
 * Time: 10:41
 */
namespace Core\HttpClient;

/**
 * Class Curl
 * @package Core\HttpClient
 */
class Curl {
    /**
     * @var string
     */
    protected $baseUri = '';

    /**
     * @var int
     */
    protected $timeout = 30;

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var array
     */
    protected $cookies = [];


    /**
     * @var bool
     */
    protected $enableProxy = false;

    /**
     * proxy
     * @var array
     */
    protected $proxy = [
        CURLOPT_PROXY => '127.0.0.1',
        CURLOPT_PROXYPORT => 80
    ];

    /**
     * Curl constructor.
     * @param string $baseUri
     * @param int $timeout
     */
    public function __construct($baseUri = '', $timeout = 30)
    {
        $this->baseUri = $baseUri;
        $this->timeout = $timeout;
    }


    /**
     * @param $method
     * @param $path
     * @param array $param
     * @return \stdClass
     */
    public function request($method, $path, $param = []){
        $method = strtoupper($method);
        $ch = curl_init();
        $options = [
            CURLOPT_URL => $this->baseUri . $path,
            CURLOPT_AUTOREFERER => false,
            CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_RETURNTRANSFER => true
        ];
        if ($method === 'POST'){
            $options += [
                CURLOPT_POST => true
            ];
            if (!empty($param)){
                $options += [
                    CURLOPT_POSTFIELDS => $param
                ];
            }
        }elseif ($method === 'GET'){
            if (!empty($param)){
                $options[CURLOPT_URL] .= '?' . http_build_query($param);
            }
            $options += [
                CURLOPT_HTTPGET => true
            ];
        }
        if ($this->headers){
            $options[CURLOPT_HTTPHEADER] = [];
            foreach ($this->headers as $key => $value){
                array_push($options[CURLOPT_HTTPHEADER], "{$key}:{$value}");
            }
        }
        if ($this->cookies){
            $options += [
                CURLOPT_COOKIE => str_replace('&', ';', http_build_query($this->cookies))
            ];
        }

        if ($this->enableProxy){
            $options += $this->proxy;
        }

        curl_setopt_array($ch, $options);
        $response = new \stdClass();
        $response->body = curl_exec($ch);
        $response->info = curl_getinfo($ch);
        return $response;
    }

    /**
     * get request
     * @param string $path
     * @param array $query
     * @return bool|string
     */
    public function get(string $path, array $query){
        return $this->request('GET', $path, $query);
    }

    /**
     * post request
     * @param string $path
     * @param array $data
     * @return \stdClass
     */
    public function post(string $path, array $data){
        return $this->request('POST', $path, $data);
    }

    /**
     * add header
     * @param $key
     * @param $value
     * @return $this
     */
    public function withHeader($key, $value){
        $this->headers[$key] = $value;
        return $this;
    }

    /**
     * add cookie
     * @param $key
     * @param $value
     * @return $this
     */
    public function withCookie($key, $value){
        $this->cookies[$key] = $value;
        return $this;
    }

    /**
     * set proxy
     * @param $host
     * @param $port
     * @return $this
     */
    public function withProxy($host, $port){
        $this->proxy[CURLOPT_PROXY] = $host;
        $this->proxy[CURLOPT_PROXYPORT] = $port;
        $this->enableProxy = true;
        return $this;
    }
}