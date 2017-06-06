<?php


namespace Unrlab\Domain\Query;


use Unrlab\Domain\Query\Dsl\BaseDSL;

class Filter extends BaseDSL
{
    const TERM = "term";
    const RANGE = "range";
}