<?php

namespace Unrlab\Client\Http;


use GuzzleHttp\Psr7\Request;

abstract class RequestBuilder
{

    protected $method;
    protected $headers = [];
    protected $body;
    protected $bodyLength = 0;
    protected $uri;

    const POST = 'POST';
    const GET = 'GET';
    const DELETE = 'DELETE';
    const PUT = 'PUT';


    public function setMethodToGet(): RequestBuilder
    {
        $this->method = static::GET;
        return $this;
    }


    public function setMethodToPost(): RequestBuilder
    {
        $this->method = static::POST;
        return $this;
    }


    public function setMethodToDelete(): RequestBuilder
    {
        $this->method = static::DELETE;
        return $this;
    }


    public function setMethodToPut(): RequestBuilder
    {
        $this->method = static::PUT;
        return $this;
    }


    public function setUri($uri): RequestBuilder
    {
        $this->uri = $uri;
        return $this;
    }

    public function setBody($data): RequestBuilder
    {
        $this->body = json_encode($data);
        $this->bodyLength = strlen($this->body);
        return $this;
    }

    abstract public function getRequest(): Request;

    public function setJsonContentType(): RequestBuilder
    {
        $this->headers['Content-Type'] = 'application/json';
        return $this;
    }
}