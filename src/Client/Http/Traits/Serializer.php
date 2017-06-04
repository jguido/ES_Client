<?php

namespace Unrlab\Client\Http\Traits;

use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\SerializerBuilder;
use Unrlab\Domain\Document\Indexable;

trait Serializer
{

    /**
     * @var \JMS\Serializer\Serializer
     */
    private $serializer;

    private function __construct()
    {
        $this->serializer = SerializerBuilder::create()->setPropertyNamingStrategy(new IdenticalPropertyNamingStrategy())->build();
    }

    /**
     * @param $data
     * @param string $format
     * @return string
     */
    protected function serialize($data, $format = 'json'): string
    {
        return $this->serializer->serialize($data, $format);
    }

    /**
     * @param string $data
     * @param string $domain
     * @return object
     */
    protected function deserialize($data, $domain)
    {
        return $this->serializer->deserialize($data, $domain, 'json');
    }

    /**
     * @param string $data
     * @param string $domain
     * @return Indexable
     */
    protected function deserializeDocument($data, $domain): Indexable
    {
        $jsonData = json_decode($data, true);
        $object = $this->deserialize(json_encode($jsonData['_source']), $domain);
        if ($object instanceof Indexable) {
            $object->setIndexId($jsonData['_id']);
        }

        return $object;
    }
    /**
     * @param $data
     * @param $domain
     * @return mixed
     */
    protected function deserializeCollection($data, $domain)
    {
        return $this->serializer->deserialize($data, 'array<'.$domain.'>', 'json');
    }
}