<?php

namespace AppBundle\Entity;


use DateInterval;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="passwordRecover")
 */
class PasswordRecover
{
    const EXPIREIN = "+5 hours"; //expiry default on 5 hours, change accordingly to accepted input for strtotime()

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \AppBundle\Entity\Person
     */
    protected $person;

    /**
     * @var string
     */
    protected $hash;

    /**
     * @var string
     */
    protected $expiryDate;

    /**
     * Constructor
     */
    public function __construct($person)
    {
        $this->person = $person;
        $this->hash = bin2hex(random_bytes(10)); //generates secure random string
        $this->expiryDate = date("Y-m-d H:i:s", strtotime (PasswordRecover::EXPIREIN));
        //sets current date + expiration on creation
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param Person $person
     */
    public function setPerson($person)
    {
        $this->person = $person;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * @param string $expiryDate
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;
    }






}