<?php


namespace Unrlab\Domain\Query\Dsl;

interface QueryDSLInterface
{
    public function getType(): string;
    public function getField(): string;
    public function getValue(): ValueInterface;
}