<?php

namespace Unrlab\Client\Http;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ClientInterface
{
    /**
     * @return RequestBuilder
     */
    public function getRequestBuilder(): RequestBuilder;

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function send(RequestInterface $request);
}
