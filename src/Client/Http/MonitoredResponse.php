<?php


namespace Unrlab\Client\Http;


use Psr\Http\Message\ResponseInterface;

class MonitoredResponse
{
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var  int
     */
    private $executionTime;

    /**
     * MonitoredResponse constructor.
     * @param ResponseInterface $response
     * @param $executionTime
     */
    public function __construct(ResponseInterface $response, $executionTime){

        $this->response = $response;
        $this->executionTime = $executionTime;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @return int
     */
    public function getExecutionTime(): int
    {
        return $this->executionTime;
    }
}
