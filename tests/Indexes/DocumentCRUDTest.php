<?php


namespace Tests\Indexes;


use JMS\Serializer\Annotation as JMS;
use Tests\Tools\ES_TestHelper;
use Tests\Tools\TestHelper;
use Unrlab\Client\Http\Client;
use Unrlab\Domain\Document\Document;
use Unrlab\Domain\Document\Indexable;
use Unrlab\Service\EsService;

class DocumentCRUDTest extends ES_TestHelper
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

    public function testShouldReturnDocumentAfterStoringADocument()
    {
        $docData = [
            'familyName' => 'family',
            'givenName' => 'given',
            'age' => 40
        ];
        $document = new Document(null, "global_search", "user", $docData, TestUser::class);

        $newDoc = $this->ES_Service->createDocument($document);

        self::assertEquals($document, $newDoc);
    }

    public function testShouldReturnDocumentAfterUpdatingADocument()
    {
        $docData1 = [
            'familyName' => 'family',
            'givenName' => 'given',
            'age' => 40
        ];
        $document1 = new Document(null, "global_search", "user", $docData1, TestUser::class);

        $newDoc1 = $this->ES_Service->createDocument($document1);

        self::assertEquals($document1, $newDoc1);

        $docData2 = [
            'familyName' => 'family1',
            'givenName' => 'given1',
            'age' => 60
        ];

        $document2 = new Document($newDoc1->getId(), "global_search", "user", $docData2, TestUser::class);

        $newDoc2 = $this->ES_Service->updateDocument($document2);

        self::assertEquals($document2, $newDoc2);
        self::assertEquals($newDoc1->getId(), $newDoc2->getId());
        self::assertNotEquals($newDoc1, $newDoc2);
    }

    public function testShouldReturnTrueWhenDeletingADocument()
    {
        $docData1 = [
            'familyName' => 'toDeleteFamily',
            'givenName' => 'toDeleteGiven',
            'age' => 18
        ];
        $document1 = new Document(null, "global_search", "user", $docData1, TestUser::class);

        $newDoc1 = $this->ES_Service->createDocument($document1);

        self::assertEquals($document1, $newDoc1);

        $response = $this->ES_Service->deleteDocument($newDoc1);

        self::assertTrue($response);
    }

    public function testShouldReturnRequestedDocument()
    {
        $docData1 = [
            'familyName' => 'family',
            'givenName' => 'given',
            'age' => 40
        ];
        $user = new TestUser('family', 'given', 40);
        $document1 = new Document(null, "global_search", "user", $docData1, TestUser::class);

        $newDoc1 = $this->ES_Service->createDocument($document1);

        self::assertEquals($document1, $newDoc1);

        $newDoc2 = $this->ES_Service->getDocument($document1);

        self::assertEquals($newDoc1->getId(), $newDoc2->getId());
        self::assertEquals($user->getAge(), $newDoc2->getData()->getAge());
    }

}

class TestUser extends Indexable
{
    /**
     * @JMS\Type("string")
     */
    private $familyName;
    /**
     * @JMS\Type("string")
     */
    private $givenName;
    /**
     * @JMS\Type("integer")
     */
    private $age;

    /**
     * User constructor.
     */
    public function __construct($familyName, $givenName, $age)
    {
        $this->familyName = $familyName;
        $this->givenName = $givenName;
        $this->age = $age;
    }

    /**
     * @return mixed
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }

    /**
     * @return mixed
     */
    public function getGivenName()
    {
        return $this->givenName;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
    }
}