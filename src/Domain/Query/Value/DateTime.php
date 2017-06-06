<?php


namespace Unrlab\Domain\Query\Value;


use Unrlab\Domain\Query\Dsl\ValueInterface;

class DateTime implements ValueInterface
{
    const GTE = "gte";
    const GT  = "gt";
    const LTE = "lte";
    const LT  = "lt";
    private $operator;
    private $dateString;

    public function __construct($operator, $dateString)
    {
        $this->operator = $operator;
        $this->dateString = $dateString;
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        return [ $this->operator => $this->dateString, "format" => "yyyy-MM-dd HH:mm:ss"];
    }
}