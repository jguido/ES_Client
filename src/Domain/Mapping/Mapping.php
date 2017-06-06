<?php


namespace Unrlab\Domain\Mapping;

use JMS\Serializer\Annotation as JMS;

class Mapping
{
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $name;
    /**
     * @var Property[]
     * @JMS\Type("array<Unrlab\Domain\Mapping\Property>")
     */
    private $properties;
    /**
     * @var bool
     * @JMS\Type("boolean")
     */
    private $indexAll;

    /**
     * Mapping constructor.
     * @param $name
     * @param Property[] $properties
     * @param bool $indexAll
     */
    public function __construct($name, array $properties = [], $indexAll = false)
    {
        $this->name = $name;
        $this->properties = $properties;
        $this->indexAll = $indexAll;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Property[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @return bool
     */
    public function isIndexAll(): bool
    {
        return $this->indexAll;
    }
}