<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/9/25
 * Time: 13:33
 */
namespace Core\Http;

/**
 * Class HttpResponse
 * @package Core\Http
 */
class HttpResponse{

    protected $data = '';

    protected $code = 200;

    /**
     * @var array
     */
    protected $pack = [];

    protected $status = [
        100 => '100 Continue',
        101 => '101 Switching Protocol',
        200 => '200 OK',
        201 => '201 Created',
        400 => '400 Bad Request',
        401 => '401 Unauthorized',
        403 => '403 Forbidden',
        404 => '404 Not Found',
        405 => 'Method Not Allowed'
    ];

    public function __construct($data)
    {
        $this->data = $data;
        $this->append("HTTP/1.1 {$this->status[$this->code]}");
        $this->addHeader('Content-Type', 'text/html');
        $this->addHeader('Content-Length', strlen($this->data));
        $this->addHeader('Connection', 'Keep-Alive');
        $this->addHeader('Server', 'php');
    }

    protected function addHeader($key, $value){
        $this->append("{$key}: {$value}");
    }

    protected function append($line){
        array_push($this->pack, $line);
    }

    public function getContent() :string {
        $this->append('');
        $this->append($this->data);
        return implode("\r\n", $this->pack);
    }
}