<?php


namespace Tests\Indexes;


use GuzzleHttp\Psr7\Response;
use Tests\Tools\ES_TestHelper;
use Tests\Tools\TestHelper;
use Unrlab\Client\Http\Client;
use Unrlab\Domain\Mapping\Index;
use Unrlab\Domain\Mapping\Mapping;
use Unrlab\Domain\Mapping\Property;
use Unrlab\Domain\Mapping\Type;
use Unrlab\Service\EsService;

class IndexClientTest extends ES_TestHelper
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var EsService
     */
    private $ES_Service;
    /**
     * @var EsService
     */
    private $Mocked_ES_Service;

    public function setUp()
    {
        parent::setUp();
        $this->client = new Client(['base_uri' => self::$EsPath]);
        $this->ES_Service = new EsService($this->client, $this->logger);
        $this->Mocked_ES_Service = new EsService($this->mockedClient, $this->logger);
    }


    public function testShouldReturnOkAfterCreatingAnIndex()
    {
        $this->addToExpectedResponses(new Response(200, [], '{"acknowledged":true,"shards_acknowledged":true}'));

        $propertyFamilyName = new Property('familyName', Type::TEXT);
        $propertyGivenName = new Property('givenName', Type::TEXT);
        $propertyAge = new Property('age', Type::INTEGER);

        $mapping = new Mapping('user', [
            $propertyFamilyName,
            $propertyGivenName,
            $propertyAge
        ]);
        $attendedBody = '{"mappings":{"user":{"_all":{"enabled":false},"properties":{"familyName":{"type":"text"},"givenName":{"type":"text"},"age":{"type":"integer"}}}}}';

        $index = new Index('global_search', [$mapping]);

        $response = $this->ES_Service->refreshIndex($index);
        $this->Mocked_ES_Service->refreshIndex($index);

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('{"acknowledged":true,"shards_acknowledged":true}', $response->getBody()->getContents());

        $r = $this->getRequestForIndex(0);
        self::assertBody($attendedBody, $r);
        self::assertMethodIsPut($r);
        self::assertUrlEquals('/global_search', $r);
    }

    public function testShouldReturnOkAfterClearingAnIndex()
    {
        $propertyFamilyName = new Property('familyName', Type::TEXT);
        $propertyGivenName = new Property('givenName', Type::TEXT);
        $propertyAge = new Property('age', Type::INTEGER);

        $mapping = new Mapping('user', [
            $propertyFamilyName,
            $propertyGivenName,
            $propertyAge
        ]);

        $index = new Index('global_search', [$mapping]);

        $this->ES_Service->refreshIndex($index);

        $response1 = $this->ES_Service->clearIndex($index);

        self::assertTrue($response1);
    }

}