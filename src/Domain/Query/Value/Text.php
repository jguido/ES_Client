<?php


namespace Unrlab\Domain\Query\Value;


use Unrlab\Domain\Query\Dsl\ValueInterface;

class Text implements ValueInterface
{
    /**
     * @var string
     */
    private $value;

    /**
     * Flat constructor.
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

}