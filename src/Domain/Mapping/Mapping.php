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
     * @param Property[] $properties
     * @return Mapping
     */
    public function setProperties(array $properties = []): self
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * @param Property $property
     * @return Mapping
     */
    public function addProperty(Property $property): self
    {
        $this->properties[] = $property;

        return $this;
    }

    /**
     * @param bool $indexAll
     * @return Mapping
     */
    public function setIndexAll(bool $indexAll): self
    {
        $this->indexAll = $indexAll;

        return $this;
    }

    /**
     * @return bool
     */
    public function isIndexAll(): bool
    {
        return $this->indexAll;
    }
}