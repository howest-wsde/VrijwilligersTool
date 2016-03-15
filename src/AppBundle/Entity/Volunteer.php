<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 */
class Volunteer implements UserInterface, \Serializable
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
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $passphrase;

    /**
     * this is not the actual email data that will be persisted to the database
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

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

    private $isActive;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->skillproficiency = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setLastUpdate(new \DateTime());
        $this->isActive = true;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getEmailFromContact()
    {
        return $this->getContact()->getEmail();
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getSalt()
    {
        // The bcrypt algorithm doesn't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
        return null;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->passphrase,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->passphrase,
        ) = unserialize($serialized);
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
     * Set username
     *
     * @param string $username
     *
     * @return Volunteer
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set passphrase
     *
     * @param string $passphrase
     *
     * @return Volunteer
     */
    public function setPassword($passphrase)
    {
        $this->passphrase = $passphrase;

        return $this;
    }

    /**
     * Get passphrase
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->passphrase;
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
        return $lastUpdate;
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
