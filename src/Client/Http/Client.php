<?php

namespace Unrlab\Client\Http;

class Client extends \GuzzleHttp\Client implements ClientInterface
{

    /**
     * @return RequestBuilder
     */
    public function getRequestBuilder(): RequestBuilder
    {
        return new GuzzleRequestBuilder();
    }
}
