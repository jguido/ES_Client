<?php

namespace Unrlab\Client\Http;

use GuzzleHttp\Psr7\Request;

class GuzzleRequestBuilder extends RequestBuilder
{
    /**
     * @inheritdoc
     */
    public function getRequest(): Request
    {
        $request = new Request($this->method, $this->uri, $this->headers, $this->body);
        $this->method = null;
        $this->uri = null;
        $this->headers = [];
        $this->body = null;
        return $request;
    }
}