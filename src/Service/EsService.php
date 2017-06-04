<?php


namespace Unrlab\Service;


use Unrlab\Client\Exception\DocumentAlreadyExistsException;
use Unrlab\Client\Exception\DocumentNotFoundException;
use Unrlab\Client\Exception\IndexAlreadyExistsException;
use Unrlab\Client\Http\BaseService;
use Unrlab\Domain\Document\Document;
use Unrlab\Domain\Mapping\Index;

class EsService extends BaseService
{

    public function refreshIndex(Index $index)
    {
        static::RegisterIndexAlreadyExistsException();
        try {

            return $this->put("/" . $index->getName(), $index);
        } catch (IndexAlreadyExistsException $e) {
            $this->delete("/" . $index->getName());

            return $this->put("/" . $index->getName(), $index);
        }
    }

    /**
     * @param Index $index
     * @return bool
     */
    public function clearIndex(Index $index): bool
    {
        static::RegisterIndexNotFoundException();

        return $this->delete("/" . $index->getName())->getStatusCode() === 200;
    }

    /**
     * @param Document $document
     * @return Document
     */
    public function getDocument(Document $document): Document
    {
        static::RegisterDocumentNotFoundException();
        $response = $this->get($document->getIndex().'/'.$document->getType().'/'.$document->getId());

        $data = $this->deserializeDocument($response, $document->getFqdn());

        return new Document($data->getIndexId(), $document->getIndex(), $document->getType(), $data, $document->getFqdn());
    }

    /**
     * @param Document $document
     * @return Document
     */
    public function createDocument(Document $document): Document
    {
        try {
            $response = $this->post($document->getIndex().'/'.$document->getType().'/'.$document->getId(), $document->getData());

            $responseArray = json_decode($response->getBody()->getContents(), true);

            return new Document($responseArray['_id'], $document->getIndex(), $document->getType(), $document->getData(), $document->getFqdn());
        } catch (DocumentAlreadyExistsException $e) {
            return $this->updateDocument($document);
        }
    }

    /**
     * @param Document $document
     * @return Document
     */
    public function updateDocument(Document $document): Document
    {
        static::RegisterDocumentNotFoundException();
        try {
            $response = $this->put($document->getIndex().'/'.$document->getType() .'/'.$document->getId(), $document->getData());

            $responseArray = json_decode($response->getBody()->getContents(), true);

            return new Document($responseArray['_id'], $document->getIndex(), $document->getType(), $document->getData(), $document->getFqdn());
        } catch (DocumentNotFoundException $e) {
            return $this->createDocument($document);
        }
    }

    /**
     * @param Document $document
     * @return bool
     */
    public function deleteDocument(Document $document): bool
    {
        static::RegisterDocumentNotFoundException();
        $response = $this->delete($document->getIndex().'/'.$document->getType().'/'.$document->getId());
        $responseArray = json_decode($response->getBody()->getContents(), true);

        return $responseArray['found'] ?? false;
    }
}