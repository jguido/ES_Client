<?php


namespace Tests\Fixtures;

use JMS\Serializer\Annotation as JMS;
use Unrlab\Domain\Document\Indexable;

/**
 * Class TestUser
 * @package Tests\Fixtures
 */
class TestUser extends Indexable
{
    /**
     * @JMS\Type("string")
     */
    private $familyName;
    /**
     * @JMS\Type("string")
     */
    private $givenName;
    /**
     * @var \DateTime
     * @JMS\Type("DateTime<'c'>")
     */
    private $lastConnection;
    /**
     * @JMS\Type("integer")
     */
    private $age;

    /**
     * User constructor.
     */
    public function __construct($familyName, $givenName, $age, $lastConnection = null)
    {
        $this->familyName = $familyName;
        $this->givenName = $givenName;
        $this->age = $age;
        $this->lastConnection = $lastConnection;
    }

    /**
     * @return mixed
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }

    /**
     * @return mixed
     */
    public function getGivenName()
    {
        return $this->givenName;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastConnection()
    {
        return $this->lastConnection;
    }
}