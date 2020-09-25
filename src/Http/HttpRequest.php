<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/9/25
 * Time: 11:39
 */
namespace Core\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class HttpParser
 * @package Core\Tools
 */
class HttpRequest implements RequestInterface {
    protected $headers = [];

    protected $cookies = [];

    protected $method;

    protected $protocol;

    protected $protocolVersion;

    protected $httpParser;

    protected $body;

    protected $post;

    protected $rawBody;

    protected $files;
    /**
     * @var UriInterface
     */
    protected $uri;

    public function __construct($data)
    {
        $this->httpParser = new HttpParser($data, $this);
    }

    public function getProtocolVersion()
    {
        // TODO: Implement getProtocolVersion() method.
    }

    public function getHeaderLine($name)
    {
        // TODO: Implement getHeaderLine() method.
    }

    public function getHeader($name)
    {
        // TODO: Implement getHeader() method.
        return $this->hasHeader($name) ? $this->headers[$name] : null;
    }

    public function getBody()
    {
        // TODO: Implement getBody() method.
    }

    public function withPost($data){
        $this->post = $data;
    }

    public function withRawBody($raw){
        $this->rawBody = $raw;
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->post;
    }

    public function getHeaders()
    {
        // TODO: Implement getHeaders() method.
    }

    public function getMethod()
    {
        // TODO: Implement getMethod() method.
        return $this->method;
    }

    public function hasHeader($name)
    {
        // TODO: Implement hasHeader() method.
        return isset($this->headers[$name]);
    }

    public function withAddedHeader($name, $value)
    {
        // TODO: Implement withAddedHeader() method.
    }

    public function withBody(StreamInterface $body)
    {
        // TODO: Implement withBody() method.
    }

    public function withFile($file){
        $this->files = $file;
    }

    public function withHeader($name, $value)
    {
        // TODO: Implement withHeader() method.
        $this->headers[$name] = $value;
    }

    public function withoutHeader($name)
    {
        // TODO: Implement withoutHeader() method.
    }

    public function withProtocolVersion($version)
    {
        $this->protocolVersion = $version;
    }
    public function getRequestTarget()
    {
        // TODO: Implement getRequestTarget() method.
    }

    public function getUri()
    {
        // TODO: Implement getUri() method.
        return $this->uri;
    }

    public function withMethod($method)
    {
        // TODO: Implement withMethod() method.
        $this->method = $method;
    }


    public function query($name, $default = ''){
        $query = $this->uri->getQuery();
        return isset($query[$name]) ? $name : $default;
    }

    public function withRequestTarget($requestTarget)
    {
        // TODO: Implement withRequestTarget() method.
    }
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        // TODO: Implement withUri() method.
        $this->uri = $uri;
    }
}