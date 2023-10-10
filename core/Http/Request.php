<?php

namespace Core\Http;

class Request
{
    private $queryStrings;
    private $body;
    private $headers;
    private $method;
    private $path;
    private $hostName;
    private $params;
    public const ALLOWED_METHODS = ['GET', 'POST', 'PUT', 'DELETE'];
    public function getQerryStrings()
    {
        return $this->queryStrings;
    }
    public function getBody()
    {
        return $this->body;
    }
    public function getMethod()
    {
        return $this->method;
    }
    public function getHostName()
    {
        return $this->hostName;
    }
    public function getParams()
    {
        return $this->params;
    }
    public function getHeaders()
    {
        return $this->headers;
    }
    public function setQerryStrings($queryStrings)
    {
        $this->queryStrings = $queryStrings;
    }
    public function setBody($body)
    {
        $this->body = $body;
    }
    public function setMethod($method)
    {
        $this->method = $method;
    }
    public function setHostName($hostName)
    {
        $this->hostName = $hostName;
    }
    public function setParams($params)
    {
        $this->params = $params;
    }
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }
    public function putParam($name, $param)
    {
        $this->params[$name] = $param;
    }
    public function getPath()
    {
        return $this->path;
    }
    public function setPath($path)
    {
        $this->path = $path;
    }
}
