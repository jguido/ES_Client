<?php

namespace Unrlab\Client\Http;

use Unrlab\Client\Exception\ExceptionHandler;
use Unrlab\Client\Http\Traits\HttpHandler;
use Unrlab\Client\Http\Traits\Serializer;
use Psr\Log\LoggerInterface;

abstract class BaseService
{

    use Serializer {
        Serializer::__construct as private setupSerializer;
    }

    use ExceptionHandler, HttpHandler;

    /**
     * BaseService constructor.
     * @param ClientInterface $client
     * @param LoggerInterface $logger
     * @internal param CacheInterface $cache
     */
    public function __construct(ClientInterface $client, LoggerInterface $logger)
    {
        $this->setupSerializer();
        $this->setLogger($logger);
        $this->client = $client;
    }
}