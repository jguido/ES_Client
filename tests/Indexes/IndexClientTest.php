<?php


namespace Tests\Indexes;


use GuzzleHttp\Psr7\Response;
use Tests\Tools\ES_TestHelper;
use Unrlab\Domain\Mapping\Index;
use Unrlab\Domain\Mapping\Mapping;
use Unrlab\Domain\Mapping\Property;
use Unrlab\Domain\Mapping\Type;
use Unrlab\Service\EsService;

class IndexClientTest extends ES_TestHelper
{
    /**
     * @var EsService
     */
    private $Mocked_ES_Service;

    public function setUp()
    {
        parent::setUp();
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

        $response = $this->Mocked_ES_Service->refreshIndex($index);

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('{"acknowledged":true,"shards_acknowledged":true}', $response->getBody()->getContents());

        $r = $this->getRequestForIndex(0);
        self::assertBody($attendedBody, $r);
        self::assertMethodIsPut($r);
        self::assertUrlEquals('/global_search', $r);
    }

    public function testShouldReturnOkAfterClearingAnIndex()
    {
        $this->addToExpectedResponses(new Response(200, [], '{"acknowledged":true,"shards_acknowledged":true}'));
        $this->addToExpectedResponses(new Response(200, [], '{"acknowledged":true}'));
        $propertyFamilyName = new Property('familyName', Type::TEXT);
        $propertyGivenName = new Property('givenName', Type::TEXT);
        $propertyAge = new Property('age', Type::INTEGER);

        $mapping = new Mapping('user', [
            $propertyFamilyName,
            $propertyGivenName,
            $propertyAge
        ]);

        $index = new Index('global_search', [$mapping]);

        $this->Mocked_ES_Service->refreshIndex($index);

        $response1 = $this->Mocked_ES_Service->clearIndex($index);

        self::assertTrue($response1);
    }

}