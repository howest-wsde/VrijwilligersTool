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
        $this->hash = random_bytes(10); //generates secure random string
        $this->expiryDate = date("Y-m-d H:i:s", strtotime (PasswordRecover::EXPIREIN));
    }

}