<?php


namespace Unrlab\Domain\Document;

/**
 * Class Indexable
 * @package Unrlab\Domain\Document
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @SuppressWarnings(PHPMD.CamelCaseVariableName)
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
abstract class Indexable
{
    /**
     * @var string
     */
    private $_id;

    /**
     * @param $id
     * @return Indexable
     */
    final public function setIndexId($id): Indexable
    {
        $this->_id = $id;

        return $this;
    }

    /**
     * @return string
     */
    final public function getIndexId(): string
    {
        return $this->_id;
    }
}
