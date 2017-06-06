<?php

namespace Unrlab\Client\Http\Traits;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Unrlab\Client\Http\ClientInterface;
use Unrlab\Client\Http\RequestBuilder;

trait HttpHandler
{
    use Loggable, Observer;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @return ClientInterface
     */
    protected function getClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * @param RequestBuilder $requestBuilder
     * @return ResponseInterface
     */
    protected function send(RequestBuilder $requestBuilder): ResponseInterface
    {
        $executeRequest = function () use ($requestBuilder) {
            return $this->client->send($requestBuilder->getRequest());
        };
        $serviceResponse = self::executeAndMonitor($executeRequest);
        $this->logNotice("Executed in " . $serviceResponse->getExecutionTime() . "ms");
        return $serviceResponse->getResponse();
    }

    /**
     * @param $uri
     * @param array $extraHeaders
     * @return ResponseInterface
     */
    protected function get($uri): ResponseInterface
    {
        $this->logNotice('GET request to uri: ' . $uri);
        $request = $this->client->getRequestBuilder()
            ->setMethodToGet()
            ->setUri($uri)
            ->setJsonContentType();


        try {
            $response = $this->send($request);
        } catch (\RuntimeException $clientException) {
            $this->throwException($clientException);
        }

        $this->logNotice('SUCCESS');
        return  $response;
    }

    /**
     * @param $uri
     * @param null $body
     * @param array $extraHeaders
     * @return ResponseInterface
     * @throws
     */
    protected function post($uri, $body = null): ResponseInterface
    {
        $this->logNotice('POST request to uri: ' . $uri);

        $request = $this->client->getRequestBuilder()
            ->setMethodToPost();

        return $this->buildRequestWithBody($request, $uri, $body);
    }

    /**
     * @param $uri
     * @param null $body
     * @param array $extraHeaders
     * @return ResponseInterface
     * @throws
     */
    protected function put($uri, $body = null): ResponseInterface
    {
        $this->logNotice('PUT request to uri: ' . $uri);

        $request = $this->client->getRequestBuilder()
            ->setMethodToPut();

        return $this->buildRequestWithBody($request, $uri, $body);
    }

    /**
     * @param $uri
     * @return ResponseInterface
     * @throws
     */
    protected function delete($uri): ResponseInterface
    {
        $this->logNotice('DELETE request to uri: ' . $uri);
        try {
            $request = $this->client->getRequestBuilder()->setJsonContentType()->setMethodToDelete()->setUri($uri);
            return $this->send($request);
        } catch (\RuntimeException $clientException) {
            $this->throwException($clientException);
        }
        $this->logNotice('SUCCESS');

        return new Response(500);
    }

    /**
     * @param \RuntimeException $clientException
     */
    protected function throwException(\RuntimeException $clientException)
    {
        $exceptionFromCode = static::getExceptionFromCode($clientException);
        if ($exceptionFromCode) {
            $this->logError("FAILURE " . get_class($exceptionFromCode));
            throw $exceptionFromCode;
        }

        static::clearExceptionStack();
        throw $clientException;
    }

    /**
     * @param RequestBuilder $request
     * @param $uri
     * @param $body
     * @return ResponseInterface
     */
    private function buildRequestWithBody(RequestBuilder $request, $uri, $body): ResponseInterface
    {
        $request
            ->setUri($uri)
            ->setJsonContentType();

        if ($body) {
            $this->logNotice('With body :' . json_encode($body));
            $request->setBody($body);
        }

        try {
            $response = $this->send($request);
            $this->logNotice('SUCCESS');

        } catch (\RuntimeException $clientException) {
            $this->throwException($clientException);
        }

        return $response;
    }
}