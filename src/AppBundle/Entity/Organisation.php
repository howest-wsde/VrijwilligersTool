<?php

namespace AppBundle\Entity;

/**
 * Organisation
 */
class Organisation
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \DateTime
     */
    private $lastUpdate;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Volunteer
     */
    private $creator;

    /**
     * @var \AppBundle\Entity\Contact
     */
    private $contact;


    /**
     * Set name
     *
     * @param string $name
     *
     * @return Organisation
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Organisation
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set lastUpdate
     *
     * @param \DateTime $lastUpdate
     *
     * @return Organisation
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    /**
     * Get lastUpdate
     *
     * @return \DateTime
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
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
     * Set id
     *
     * @return Organisation
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }


    /**
     * Set creator
     *
     * @param \AppBundle\Entity\Volunteer $creator
     *
     * @return Organisation
     */
    public function setCreator(\AppBundle\Entity\Volunteer $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \AppBundle\Entity\Volunteer
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set contact
     *
     * @param \AppBundle\Entity\Contact $contact
     *
     * @return Organisation
     */
    public function setContact(\AppBundle\Entity\Contact $contact = null)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return \AppBundle\Entity\Contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString()
    {
     return strval($this->getId() + " " + $this->getName());
    }
    /*
    function __toString()
    {
        return "id: ".$this->getId().
        ", name: ".$this->getName().
        ", description: ".$this->getDescription().
        " contactId: ".$this->getContactid()->getId().
        " creatorID: ".$this->getContactid().$this->getId();
    }
    */
}
