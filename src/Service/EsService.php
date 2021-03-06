<?php


namespace Unrlab\Service;


use Unrlab\Client\Exception\DocumentAlreadyExistsException;
use Unrlab\Client\Exception\DocumentNotFoundException;
use Unrlab\Client\Exception\IndexAlreadyExistsException;
use Unrlab\Client\Http\BaseService;
use Unrlab\Domain\Document\Document;
use Unrlab\Domain\Document\Indexable;
use Unrlab\Domain\Mapping\Index;
use Unrlab\Domain\Query\Query;

class EsService extends BaseService
{

    public function refreshIndex(Index $index)
    {
        static::registerIndexAlreadyExistsException();
        try {

            $response = $this->put("/" . $index->getName(), $index);

            return $response;
        } catch (IndexAlreadyExistsException $e) {
            $this->clearIndex($index);

            return $this->put("/" . $index->getName(), $index);
        }
    }

    /**
     * @param Index $index
     * @return bool
     */
    public function clearIndex(Index $index): bool
    {
        static::registerIndexNotFoundException();

        $response = $this->delete("/" . $index->getName());

        return $response->getStatusCode() === 200;
    }

    /**
     * @param Document $document
     * @return Document
     */
    public function getDocument(Document $document): Document
    {
        static::registerDocumentNotFoundException();
        $response = $this->get($document->getIndex().'/'.$document->getType().'/'.$document->getId());

        $data = $this->deserializeDocument($response->getBody()->getContents(), $document->getFqdn());

        return new Document($data->getIndexId(), $document->getIndex(), $document->getType(), $data, $document->getFqdn());
    }

    /**
     * @param Document $document
     * @return Document
     */
    public function createDocument(Document $document): Document
    {
        try {
            if (!is_array($document->getData())) {
                $body = json_decode($this->serialize($document->getData(), 'json'), true);
            } else {
                $body = $document->getData();
            }
            $response = $this->post($document->getIndex().'/'.$document->getType().'/'.$document->getId(), $body);

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
        static::registerDocumentNotFoundException();
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
        static::registerDocumentNotFoundException();
        $response = $this->delete($document->getIndex().'/'.$document->getType().'/'.$document->getId());

        $responseArray = json_decode($response->getBody()->getContents(), true);

        return $responseArray['found'] ?? false;
    }

    /**
     * @param Query $query
     * @param string|null $class
     * @return array|Document
     */
    public function query(Query $query, $domain = null): array
    {
        $route = $query->buildRoute();
        $response = $this->post($route, $query);

        return $this->fetchQueryResult(json_decode($response->getBody()->getContents(), true), $domain);
    }

    public function clearType($index, $type)
    {
        $response = $this->post("/" . $index . "/" . $type . "/_delete_by_query", ["query" => ["match_all" => new \stdClass()]]);
        return $response->getStatusCode() === 200;
    }
    /**
     * @param array $queryResult
     * @param string|null $domain
     * @return array
     */
    protected function fetchQueryResult(array $queryResult, $domain = null)
    {
        $result = [];
        if(array_key_exists('hits', $queryResult) && array_key_exists('hits', $queryResult['hits'])) {
            $hits = $queryResult['hits']['hits'];
            $result = array_map(function($hit) use ($domain){
                if ($domain) {
                    $object = $this->deserialize(json_encode($hit['_source']), $domain);
                    if ($object instanceof Indexable) {
                        $object->setIndexId($hit['_id']);
                    }

                    return $object;
                } else {
                    return $hit['_source'];
                }
            }, $hits);
        }

        return $result;
    }
}
