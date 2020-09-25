<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/9/25
 * Time: 13:42
 */
namespace Core\Http;

use function GuzzleHttp\Psr7\parse_query;
use Psr\Http\Message\UriInterface;

/**
 * Class Uri
 * @package Core\Http
 */
class Uri implements UriInterface{
    protected $uri;

    protected $host;

    protected $path;

    protected $port = 80;

    protected $query = [];


    public function __construct($uri)
    {
        $this->uri = $uri;
        $uriInfo = parse_url($uri);
        $this->path = $uriInfo['path'];
        if (!empty($uriInfo['query'])){
            $this->query = parse_query($uriInfo['query']);
        }
    }

    public function getAuthority()
    {
        // TODO: Implement getAuthority() method.
    }

    public function getFragment()
    {
        // TODO: Implement getFragment() method.
    }

    public function getHost()
    {
        // TODO: Implement getHost() method.
        return $this->host;
    }

    public function getPath()
    {
        // TODO: Implement getPath() method.
        return $this->path;
    }

    public function getPort()
    {
        // TODO: Implement getPort() method.
        return $this->port;
    }

    public function getQuery()
    {
        // TODO: Implement getQuery() method.
        return $this->query;
    }

    public function getScheme()
    {
        // TODO: Implement getScheme() method.
    }

    public function getUserInfo()
    {
        // TODO: Implement getUserInfo() method.
    }

    public function withFragment($fragment)
    {
        // TODO: Implement withFragment() method.
    }

    public function withHost($host)
    {
        // TODO: Implement withHost() method.
    }

    public function withPath($path)
    {
        // TODO: Implement withPath() method.
    }

    public function withPort($port)
    {
        // TODO: Implement withPort() method.
    }

    public function withQuery($query)
    {
        // TODO: Implement withQuery() method.
    }

    public function withScheme($scheme)
    {
        // TODO: Implement withScheme() method.
    }

    public function withUserInfo($user, $password = null)
    {
        // TODO: Implement withUserInfo() method.
    }

    public function __toString()
    {
        return $this->uri;
    }
}
