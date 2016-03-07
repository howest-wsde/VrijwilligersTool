<?php

namespace AppBundle\Entity;

/**
 * Testimonial
 */
class Testimonial
{
    /**
     * @var string
     */
    private $value;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Volunteer
     */
    private $sender;

    /**
     * @var \AppBundle\Entity\Volunteer
     */
    private $receiver;


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
     * @param \AppBundle\Entity\Volunteer $sender
     *
     * @return Testimonial
     */
    public function setSender(\AppBundle\Entity\Volunteer $sender = null)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return \AppBundle\Entity\Volunteer
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set receiver
     *
     * @param \AppBundle\Entity\Volunteer $receiver
     *
     * @return Testimonial
     */
    public function setReceiver(\AppBundle\Entity\Volunteer $receiver = null)
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * Get receiver
     *
     * @return \AppBundle\Entity\Volunteer
     */
    public function getReceiver()
    {
        return $this->receiver;
    }
}

