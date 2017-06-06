<?php


namespace Tests\Indexes;

use GuzzleHttp\Psr7\Response;
use Tests\Fixtures\TestUser;
use Tests\Tools\ES_TestHelper;
use Unrlab\Domain\Document\Document;
use Unrlab\Service\EsService;

class DocumentCRUDTest extends ES_TestHelper
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

    public function testShouldReturnDocumentAfterStoringADocument()
    {
        $this->addToExpectedResponses(new Response(201, [], file_get_contents(__DIR__.'/../Fixtures/document_create_success.json')));
        $docData = [
            'familyName' => 'family',
            'givenName' => 'given',
            'age' => 40
        ];
        $document = new Document('9D8EAC55-355F-619A-5A62-0C4FE4A7020A', "global_search", "user", $docData, TestUser::class);

        $newDoc = $this->Mocked_ES_Service->createDocument($document);

        self::assertEquals($document->getData(), $newDoc->getData());
    }

    public function testShouldReturnDocumentAfterUpdatingADocument()
    {
        $this->addToExpectedResponses(new Response(201, [], file_get_contents(__DIR__.'/../Fixtures/document_create_success.json')));
        $this->addToExpectedResponses(new Response(201, [], file_get_contents(__DIR__.'/../Fixtures/document_update_success.json')));

        $docData1 = [
            'familyName' => 'family',
            'givenName' => 'given',
            'age' => 40
        ];
        $document1 = new Document(null, "global_search", "user", $docData1, TestUser::class);

        $newDoc1 = $this->Mocked_ES_Service->createDocument($document1);

        self::assertEquals($document1->getData(), $newDoc1->getData());

        $docData2 = [
            'familyName' => 'family1',
            'givenName' => 'given1',
            'age' => 60
        ];

        $document2 = new Document($newDoc1->getId(), "global_search", "user", $docData2, TestUser::class);

        $newDoc2 = $this->Mocked_ES_Service->updateDocument($document2);

        self::assertEquals($document2->getData(), $newDoc2->getData());
        self::assertNotEquals($newDoc1->getData(), $newDoc2->getData());
    }

    public function testShouldReturnTrueWhenDeletingADocument()
    {
        $this->addToExpectedResponses(new Response(201, [], file_get_contents(__DIR__.'/../Fixtures/document_create_success.json')));
        $this->addToExpectedResponses(new Response(200, [], file_get_contents(__DIR__.'/../Fixtures/document_delete_success.json')));
        $docData1 = [
            'familyName' => 'toDeleteFamily',
            'givenName' => 'toDeleteGiven',
            'age' => 18
        ];
        $document1 = new Document(null, "global_search", "user", $docData1, TestUser::class);

        $newDoc1 = $this->Mocked_ES_Service->createDocument($document1);

        self::assertEquals($document1->getData(), $newDoc1->getData());

        $response = $this->Mocked_ES_Service->deleteDocument($newDoc1);

        self::assertTrue($response);
    }

    public function testShouldReturnTrueWhenDeletingADocumentType()
    {
        $this->addToExpectedResponses(new Response(200, [], file_get_contents(__DIR__.'/../Fixtures/document_clear_type_success.json')));
        $document1 = new Document(null, "global_search", "user", [], TestUser::class);

        $response = $this->Mocked_ES_Service->clearType($document1->getIndex(), $document1->getType());

        self::assertTrue($response);
    }

    public function testShouldReturnRequestedDocument()
    {
        $this->addToExpectedResponses(new Response(201, [], file_get_contents(__DIR__.'/../Fixtures/document_create_success.json')));
        $this->addToExpectedResponses(new Response(200, [], file_get_contents(__DIR__.'/../Fixtures/document_get_success.json')));
        $docData1 = [
            'familyName' => 'family',
            'givenName' => 'given',
            'age' => 40
        ];
        $user = new TestUser('family', 'given', 40);
        $document1 = new Document(null, "global_search", "user", $docData1, TestUser::class);

        $newDoc1 = $this->Mocked_ES_Service->createDocument($document1);

        self::assertEquals($document1->getData(), $newDoc1->getData());

        $newDoc2 = $this->Mocked_ES_Service->getDocument($document1);

        self::assertEquals($newDoc1->getData()['familyName'], $newDoc2->getData()->getFamilyName());
        self::assertEquals($user->getAge(), $newDoc2->getData()->getAge());
    }

}

