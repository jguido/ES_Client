<?php

namespace Tests\Tools;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use Monolog\Handler\NullHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Unrlab\Client\Http\GuzzleClient;

abstract class TestHelper extends TestCase
{
    use AssertHelper, StringUtil;

    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var MockHandler
     */
    protected $mock;
    /**
     * @var HandlerStack
     */
    protected $handler;
    /**
     * @var array
     */
    protected $stack;
    /**
     * @var GuzzleClient
     */
    protected $mockedClient;

    public function setUp()
    {
        $nullHandler = new NullHandler();
        $this->logger = new Logger('ES', [$nullHandler]);
        $this->mock = new MockHandler([]);
        $this->handler = HandlerStack::create($this->mock);

        $this->stack = [];
        $history = Middleware::history($this->stack);
        $this->handler->push($history);

        $this->mockedClient = new GuzzleClientTest($this->handler);
    }

    /**
     * @return GuzzleClient
     */
    public function getMockedClient()
    {
        return $this->mockedClient;
    }

    /**
     * @return array
     */
    public function getStack()
    {
        return $this->stack;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->getRequestForIndex(0);
    }

    /**
     * @param $index
     * @return Request
     */
    public function getRequestForIndex($index)
    {
        return $this->stack[$index]['request'];
    }

    /**
     * @param $responses
     */
    protected function addToExpectedResponses($responses)
    {
        $this->mock->append($responses);
    }

}