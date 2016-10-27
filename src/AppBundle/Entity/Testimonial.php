<?php

namespace AppBundle\Entity;

/**
 * Testimonial
 */
class Testimonial extends EntityBase
{
    /**
     * @var string
     */
    private $value;

    /**
     * @var \DateTime
     */
    private $lastUpdate = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Person
     */
    private $sender;

    /**
     * @var \AppBundle\Entity\Person
     */
    private $receiver;

    /**
     * @var boolean
     */
    private $approved;

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Testimonial
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set id
     *
     * @return Testimonial
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set sender
     *
     * @param \AppBundle\Entity\Person $sender
     *
     * @return Testimonial
     */
    public function setSender(\AppBundle\Entity\Person $sender = null)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return \AppBundle\Entity\Person
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set receiver
     *
     * @param \AppBundle\Entity\Person $receiver
     *
     * @return Testimonial
     */
    public function setReceiver(\AppBundle\Entity\Person $receiver = null)
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * Get receiver
     *
     * @return \AppBundle\Entity\Person
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * Set approved
     *
     * @param boolean $approved
     *
     * @return Testimonial
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;

        return $this;
    }

    /**
     * Get approved
     *
     * @return boolean
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    function __toString()
    {
        return json_encode( array(
            "Entity" => $this->getClassName(),
            "Id" => $this->getId(),
            "Values" => array(
                "Value" => $this->getValue(),
                "Sender" => $this->getSender(),
                "Receiver" => $this->getReceiver(),
                "Approved" => $this->getApproved()
            )
        ));
    }

    /**
     * Get name for url
     *
     * @return string
     */
    public function getUrlId()
    {
        return str_replace(" ", "-", $this->sender);
    }
    /**
     * @var boolean
     */
    private $receiverIsVacancy;


    /**
     * Set receiverIsVacancy
     *
     * @param boolean $receiverIsVacancy
     *
     * @return Testimonial
     */
    public function setReceiverIsVacancy($receiverIsVacancy)
    {
        $this->receiverIsVacancy = $receiverIsVacancy;

        return $this;
    }

    /**
     * Get receiverIsVacancy
     *
     * @return boolean
     */
    public function getReceiverIsVacancy()
    {
        return $this->receiverIsVacancy;
    }
    /**
     * @var \AppBundle\Entity\Vacancy
     */
    private $receiverVacancy;

    /**
     * @var \AppBundle\Entity\Person
     */
    private $receiverPerson;


    /**
     * Set receiverVacancy
     *
     * @param \AppBundle\Entity\Vacancy $receiverVacancy
     *
     * @return Testimonial
     */
    public function setReceiverVacancy(\AppBundle\Entity\Vacancy $receiverVacancy = null)
    {
        $this->receiverVacancy = $receiverVacancy;

        return $this;
    }

    /**
     * Get receiverVacancy
     *
     * @return \AppBundle\Entity\Vacancy
     */
    public function getReceiverVacancy()
    {
        return $this->receiverVacancy;
    }

    /**
     * Set receiverPerson
     *
     * @param \AppBundle\Entity\Person $receiverPerson
     *
     * @return Testimonial
     */
    public function setReceiverPerson(\AppBundle\Entity\Person $receiverPerson = null)
    {
        $this->receiverPerson = $receiverPerson;

        return $this;
    }

    /**
     * Get receiverPerson
     *
     * @return \AppBundle\Entity\Person
     */
    public function getReceiverPerson()
    {
        return $this->receiverPerson;
    }
}
