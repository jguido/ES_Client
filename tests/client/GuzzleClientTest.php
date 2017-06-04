<?php


use PHPUnit\Framework\TestCase;

class GuzzleClientTest extends TestCase
{
    public function testClientWithGoodPaths()
    {
        $routes = [
            'test1' => 'http:0.0.0.0:80',
            'test2' => 'http:0.0.0.0:81',
            'test3' => 'http:0.0.0.0:82'
        ];
        $client = new \Unrlab\Client\Http\GuzzleClient($routes, 'test1');

        self::assertTrue($client instanceof \Unrlab\Client\Http\Client);
        self::assertTrue($client instanceof \Unrlab\Client\Http\ClientInterface);
        self::assertTrue($client->getRequestBuilder() instanceof \Unrlab\Client\Http\RequestBuilder);
    }
    public function testClientWithBadPaths()
    {
        $this->expectException(InvalidArgumentException::class);
        $routes = [
            'test1' => 'http:0.0.0.0:80',
            'test2' => 'http:0.0.0.0:81',
            'test3' => 'http:0.0.0.0:82'
        ];
        $client = new \Unrlab\Client\Http\GuzzleClient($routes, 'test5');
    }

}