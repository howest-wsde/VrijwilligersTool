<?php

namespace AppBundle\Entity;

/**
 * Volunteer
 */
class Volunteer
{
    /**
     * @var string
     */
    private $firstname;

    /**
     * @var string
     */
    private $lastname;

    /**
     * @var \DateTime
     */
    private $lastUpdate = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Contact
     */
    private $contact;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $skillproficiency;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->skillproficiency = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Volunteer
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Volunteer
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Get full name
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->firstname." ".$this->lastname;
    }

    /**
     * Set lastUpdate
     *
     * @param \DateTime $lastUpdate
     *
     * @return Volunteer
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
     * Set contact
     *
     * @param \AppBundle\Entity\Contact $contact
     *
     * @return Volunteer
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
     * Add skillproficiency
     *
     * @param \AppBundle\Entity\Skillproficiency $skillproficiency
     *
     * @return Volunteer
     */
    public function addSkillproficiency(\AppBundle\Entity\Skillproficiency $skillproficiency)
    {
        $this->skillproficiency[] = $skillproficiency;

        return $this;
    }

    /**
     * Remove skillproficiency
     *
     * @param \AppBundle\Entity\Skillproficiency $skillproficiency
     */
    public function removeSkillproficiency(\AppBundle\Entity\Skillproficiency $skillproficiency)
    {
        $this->skillproficiency->removeElement($skillproficiency);
    }

    /**
     * Get skillproficiency
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSkillproficiencies()
    {
        return $this->skillproficiency;
    }

    private function getSkillsString()
    {
        $result = "";
        $proficiencies = $this->getSkillproficiencies();
        foreach ($proficiencies as $index => $skill)
        {
            $result .= "<br />".$skill;
        }
        return $result;
    }
    
    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    function __toString()
    {
        return "Volunteer: {id: ".$this->getId().
        ", firstname: ".$this->getFirstname().
        ", lastname: ".$this->getLastname().
        ", contact: ".$this->getContact().
        ", skills: {".$this->getSkillsString()."}";
    }
}
