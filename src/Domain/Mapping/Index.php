<?php


namespace Unrlab\Domain\Mapping;

use JMS\Serializer\Annotation as JMS;

class Index implements \JsonSerializable
{
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $name;
    /**
     * @var Mapping[]
     * @JMS\Type("array<Unrlab\Domain\Mapping\Mapping>")
     */
    private $mappings;

    /**
     * Index constructor.
     * @param $name
     * @param Mapping[] $mappings
     */
    public function __construct($name, array $mappings = [])
    {
        $this->name = $name;
        $this->mappings = $mappings;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        $data = [
            'mappings' => []
        ];
        foreach ($this->mappings as $mapping) {
            $properties = [];
            foreach ($mapping->getProperties() as $property) {
                $properties[$property->getName()] = ['type' => $property->getType()];
            }
            $data['mappings'][$mapping->getName()] = [
                '_all' => ['enabled' => $mapping->isIndexAll()],
                'properties' => $properties
            ];
        }

        return $data;
    }
}
