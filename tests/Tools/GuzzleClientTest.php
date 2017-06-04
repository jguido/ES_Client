<?php

namespace Tests\Tools;


use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Unrlab\Client\Http\ClientInterface;
use Unrlab\Client\Http\GuzzleRequestBuilder;
use Unrlab\Client\Http\RequestBuilder;

class GuzzleClientTest extends Client implements ClientInterface
{
    public function __construct(HandlerStack $handler)
    {
        parent::__construct(['handler' => $handler]);
    }

    /**
     * @inheritdoc
     */
    public function getRequestBuilder(): RequestBuilder
    {
        return new GuzzleRequestBuilder();
    }
}