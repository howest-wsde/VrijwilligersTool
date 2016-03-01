<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="User", uniqueConstraints={@ORM\UniqueConstraint(name="Id", columns={"Id"})}, indexes={@ORM\Index(name="FKUser301874", columns={"ContactId"})})
 * @ORM\Entity
 */
class User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="Id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="FirstName", type="string", length=100, nullable=false)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="LastName", type="string", length=100, nullable=false)
     */
    private $lastname;

    /**
     * @var integer
     *
     * @ORM\Column(name="SkillId", type="integer", nullable=true)
     */
    private $skillid;

    /**
     * @var \AppBundle\Entity\Contact
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Contact")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ContactId", referencedColumnName="Id")
     * })
     */
    private $contactid;



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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
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
     * @return User
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
     * Set skillid
     *
     * @param integer $skillid
     *
     * @return User
     */
    public function setSkillid($skillid)
    {
        $this->skillid = $skillid;

        return $this;
    }

    /**
     * Get skillid
     *
     * @return integer
     */
    public function getSkillid()
    {
        return $this->skillid;
    }

    /**
     * Set contactid
     *
     * @param \AppBundle\Entity\Contact $contactid
     *
     * @return User
     */
    public function setContactid(\AppBundle\Entity\Contact $contactid = null)
    {
        $this->contactid = $contactid;

        return $this;
    }

    /**
     * Get contactid
     *
     * @return \AppBundle\Entity\Contact
     */
    public function getContactid()
    {
        return $this->contactid;
    }
}
