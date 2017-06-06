<?php


namespace Tests\Indexes;


use GuzzleHttp\Psr7\Response;
use Tests\Fixtures\TestUser;
use Tests\Tools\ES_TestHelper;
use Tests\Tools\TestHelper;
use Unrlab\Client\Http\Client;
use Unrlab\Domain\Document\Document;
use Unrlab\Domain\Mapping\Index;
use Unrlab\Domain\Mapping\Mapping;
use Unrlab\Domain\Mapping\Property;
use Unrlab\Domain\Mapping\Type;
use Unrlab\Domain\Query\Filter;
use Unrlab\Domain\Query\Must;
use Unrlab\Domain\Query\Query;
use Unrlab\Domain\Query\Value\Date;
use Unrlab\Domain\Query\Value\DateTime;
use Unrlab\Domain\Query\Value\Text;
use Unrlab\Service\EsService;

class QueriesTest extends ES_TestHelper
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


    public function testShouldReturnListOfDataWhenQuerying()
    {
        $this->addToExpectedResponses(new Response(200, [], '{"acknowledged":true,"shards_acknowledged":true}'));

        $propertyFamilyName = new Property('familyName', Type::TEXT);
        $propertyGivenName = new Property('givenName', Type::TEXT);
        $propertyAge = new Property('age', Type::INTEGER);
        $lastConnection = new Property('lastConnection', Type::DATE);

        $this->ES_Service->clearType("global_search", "user");
        sleep(1);

        $mapping = new Mapping('user', [
            $propertyFamilyName,
            $propertyGivenName,
            $propertyAge,
            $lastConnection
        ]);

        $index = new Index('global_search', [$mapping]);
        $this->ES_Service->refreshIndex($index);

        sleep(1);

        $this->buildUsers(50, $index);

        sleep(2);
        $now = new \DateTime();
        $now->add(new \DateInterval("P10D"));

        $queryBuilder = new Query();
        $query = $queryBuilder
            ->setIndex($index)
            ->setType("user")
            ->addFilter(new Filter(Filter::RANGE, "lastConnection", new DateTime(Date::GTE, $now->format("Y-m-d H:i:s"))));

        $result = $this->ES_Service->query($query, TestUser::class);
        $result1 = $this->ES_Service->query($query);

        self::assertTrue($result[0] instanceof TestUser);
        self::assertTrue(is_array($result1[0]));
    }

    protected function buildUsers($nb = 50, Index $index)
    {
        $this->ES_Service->clearIndex($index);
        $age = 18;
        foreach (range(1, $nb) as $iter) {
            $date = new \DateTime();
            $date->add(new \DateInterval("P".$iter."D"));
            $age += $iter;
            $docData1 = [
                'familyName' => 'family'.$iter,
                'givenName' => 'given'.$iter,
                'age' => $age,
                'lastConnection' => $date
            ];

            $this->ES_Service->createDocument(new Document(null, $index->getName(), "user", $docData1, TestUser::class));
        }
    }
}