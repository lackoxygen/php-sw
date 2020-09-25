<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/9/25
 * Time: 13:48
 */
namespace Core\Http;

use function GuzzleHttp\Psr7\parse_query;
use Swoole\Coroutine\System;

/**
 * Class HttpParser
 * @package Core\Http
 */
class HttpParser{
    protected $httpRequest;

    public function __construct($data, HttpRequest $httpRequest)
    {
        $this->httpRequest = $httpRequest;

        $this->parse($data);
    }

    protected function parse($data){
        $body = explode("\r\n", $data);
        [$method, $uri, $protocolVersion] = explode(' ', array_shift($body));
        $uri = new Uri($uri);
        $this->httpRequest->withMethod($method);
        $this->httpRequest->withUri($uri);
        [$_, $version] = explode('/', $protocolVersion);
        $this->httpRequest->withProtocolVersion($version);
        while ($line = array_shift($body)){
            if (strlen($line) > 0) {
                if (strpos($line,  ':') !== false){
                    [$name, $value] = explode(': ', $line);
                    $this->httpRequest->withHeader($name, $value);
                }
            }
        }
        $contentTypes = explode('; ', $this->httpRequest->getHeader('Content-Type'));
        if ($contentTypes[0] === 'application/x-www-form-urlencoded'){
            $this->httpRequest->withPost(parse_query($data));
        }elseif ($contentTypes[0] === 'multipart/form-data'){
            [$_, $boundary] = explode('=', $contentTypes[1]);
            $flag = false;
            $stream = false;
            $buffer = '';
            $disposition = [];
            $files = [];
            $formData = [];
            foreach ($body as $line){
                if ($line === '--' . $boundary){
                    $flag = !$flag;
                    if (!$flag){
                        if (!empty($disposition['filename'])){
                            $files[$disposition['name']] = $disposition;
                            $files[$disposition['name']]['buffer'] = $buffer;
                            unset($files[$disposition['name']]['name']);
                        }else{
                            $formData[$disposition['name']] = $disposition;
                            unset($files[$disposition['name']]['name']);
                        }
                        $buffer = '';
                        $stream = false;
                        $disposition = [];
                    }
                    continue;
                }elseif ($line === '--' . $boundary . '--'){
                    break;
                }
                if (strpos($line, 'Content-Disposition') !== false){
                    [$_, $parts] = explode(': ', $line);
                    foreach (explode('; ', $parts) as $part){
                        if ($part == 'form-data'){
                            continue;
                        }
                        $disposition = array_merge($disposition, parse_query(str_replace('"', '', $part)));
                    }
                    continue;
                }elseif (strpos($line, 'Content-Type') !== false){
                    $stream = true;
                    continue;
                }

                if ($stream){
                    $buffer .= $line;
                }
            }

            if ($formData){
                $this->httpRequest->withPost($formData);
            }

            if ($files){
                foreach ($files as $k => $file){
                    $tmpFile = '/tmp/'.md5(uniqid() . microtime(true));
                    System::writeFile($tmpFile, $file['buffer']);
                    unset($files[$k]['buffer']);
                    $files[$k]['tmp'] = $tmpFile;
                }
                $this->httpRequest->withFile($files);
            }
        }
    }
}