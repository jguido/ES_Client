<?php


namespace Unrlab\Domain\Document;

use Unrlab\Tools\Guid;

/**
 * Class Document
 * @package Unrlab\Domain\Document
 */
class Document
{
    /**
     * @var string
     */
    protected $_id;

    /**
     * @var string
     */
    protected $index;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var string
     */
    protected $fqdn;

    /**
     * Document constructor.
     * @param string $_id
     * @param string $index
     * @param string $type
     * @param $data
     * @param $fqdn
     */
    public function __construct($_id = null, $index, $type, $data, $fqdn)
    {
        $this->_id = $_id ?? Guid::next();
        $this->index = $index;
        $this->type = $type;
        $this->data = $data;
        $this->fqdn = $fqdn;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->_id;
    }

    /**
     * @return string
     */
    public function getIndex(): string
    {
        return $this->index;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getFqdn(): string
    {
        return $this->fqdn;
    }
}