<?php


namespace Tests\Tools;


use GuzzleHttp\Psr7\Request;

trait AssertHelper
{
    /**
     * @param Request $r
     */
    public static function assertMethodIsPost(Request $r)
    {
        self::assertEquals('POST', $r->getMethod());
    }

    /**
     * @param Request $r
     */
    public static function assertMethodIsDelete(Request $r)
    {
        self::assertEquals('DELETE', $r->getMethod());
    }

    /**
     * @param Request $r
     */
    public static function assertMethodIsGet(Request $r)
    {
        self::assertEquals('GET', $r->getMethod());
    }

    /**
     * @param Request $r
     */
    public static function assertMethodIsPut(Request $r)
    {
        self::assertEquals('PUT', $r->getMethod());
    }

    /**
     * @param $expected
     * @param Request $r
     */
    public static function assertUrlEquals($expected, Request $r)
    {
        $uri = $r->getUri()->getPath();
        if ($r->getUri()->getQuery()) {
            $uri .= '?'.$r->getUri()->getQuery();
        }
        self::assertEquals($expected, $uri);
    }

    /**
     * @param $headerName
     * @param $value
     * @param Request $r
     */
    public static function assertHeaderContainValue($headerName, $value, Request $r){
        self::assertEquals($value, $r->getHeader($headerName)[0]);
    }

    /**
     * @param $body
     * @param Request $r
     */
    public static function assertBody($body, Request $r)
    {

        $sentBody = json_decode($body, true);
        $bodyReceivedByClient = json_decode($r->getBody()->getContents(), true);

        self::assertNotNull($sentBody, 'Sent body shouldnt be null');
        self::assertNotNull($bodyReceivedByClient, 'Http client should have received a body');


        self::assertEquals($sentBody, $bodyReceivedByClient);
    }

    /**
     * @param Request $r
     */
    protected static function assertRequestsContentTypeIsApplicationJson(Request $r)
    {
        self::assertEquals('application/json', $r->getHeader('Content-Type')[0]);
    }
}