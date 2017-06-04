<?php


namespace Unrlab\Domain\Query;


use Unrlab\Domain\Query\DSL\BaseDSL;

class Filter extends BaseDSL
{
    const TERM = "term";
    const RANGE = "range";
}