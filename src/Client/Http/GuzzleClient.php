<?php

namespace Unrlab\Client\Http;

class GuzzleClient extends Client implements ClientInterface
{
    public function __construct(array $routes, $env = 'prod')
    {
        if (!array_key_exists($env, $routes)) {
            throw new \InvalidArgumentException(sprintf('%s not found in routes [%s]', $env, implode(',', $routes)));
        }
        parent::__construct(array('base_uri' => $routes[$env]));
    }

    /**
     * @return RequestBuilder
     */
    public function getRequestBuilder(): RequestBuilder
    {
        return new GuzzleRequestBuilder();
    }
}