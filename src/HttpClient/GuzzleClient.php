<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/9/24
 * Time: 15:07
 */
namespace Core\HttpClient;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
/**
 * Class GuzzleClient
 * @package Core\HttpClient
 */
class GuzzleClient{

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var int
     */
    protected $timeout = 30;

    /**
     * @var array
     */
    protected $cookies = [];

    /**
     * @var array
     */
    protected $headers = [];


    /**
     * GuzzleClient constructor.
     * @param string $baseUri
     */
    public function __construct($baseUri = '')
    {
        $config = [];
        if (!empty($baseUri)){
            $config['base_uri'] = $baseUri;
        }
        $this->client = new Client($config);
    }

    /**
     * @param $url
     * @param array $query
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($url, $query = []){
        return $this->request(
            'GET',
            $url,
            [RequestOptions::QUERY => $query]
        );
    }

    /**
     * @param $url
     * @param array $data
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post($url, array $data = []){
        return $this->request(
            'POST',
            $url,
            [RequestOptions::FORM_PARAMS => $data]
        );
    }

    /**
     * @param int $timeout
     * @return GuzzleClient
     */
    public function withTimeout(int $timeout): GuzzleClient
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * @param array $cookies
     * @return GuzzleClient
     */
    public function withCookies(array $cookies): GuzzleClient
    {
        $this->cookies = $cookies;
        return $this;
    }

    /**
     * @param array $headers
     * @return GuzzleClient
     */
    public function withHeaders(array $headers): GuzzleClient
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @param string $method
     * @param $url
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($method, $url, $options = []){
        $response = $this->client->request(
            $method,
            $url,
            $this->mergeOptions($options)
        );
        return $response;
    }

    /**
     * @param $options
     * @return array
     */
    protected function mergeOptions($options) :array {
        $options += [
            RequestOptions::TIMEOUT => $this->timeout,
            RequestOptions::COOKIES => $this->cookies,
            RequestOptions::HEADERS => $this->headers
        ];
        return $options;
    }
}