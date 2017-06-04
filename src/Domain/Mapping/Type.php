<?php


namespace Unrlab\Domain\Mapping;


class Type
{
    const TEXT      = "text";
    const INTEGER   = "integer";
    const KEYWORD   = "keyword";
    const DATE      = "date";
    const BOOLEAN   = "boolean";
    const RANGE     = "range";
    const BINARY    = "binary";
    const GEO_POINT = "geo_point";
    const GEO_SHAPE = "geo_shape";
    const IP        = "ip";
    const NESTED    = "nested";
    const OBJECT    = "object";
    const ARRAY     = "array";
}