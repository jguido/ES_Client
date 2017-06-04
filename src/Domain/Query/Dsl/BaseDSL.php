<?php


namespace Unrlab\Domain\Query\Dsl;

abstract class BaseDSL implements QueryDSLInterface, \JsonSerializable
{
    /**
     * @var string
     */
    protected $type;
    /**
     * @var string
     */
    protected $field;
    /**
     * @var ValueInterface
     */
    protected $value;

    /**
     * Must constructor.
     * @param string $type
     * @param string $field
     * @param ValueInterface $value
     */
    public function __construct($type, $field, $value)
    {
        $this->type = $type;
        $this->field = $field;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return ValueInterface
     */
    public function getValue(): ValueInterface
    {
        return $this->value;
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
        return [
            $this->getType() => [ $this->getField() => $this->getValue()->getValue() ]
        ];
    }
}