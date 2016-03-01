<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Organisation
 *
 * @ORM\Table(name="Organisation", uniqueConstraints={@ORM\UniqueConstraint(name="Name", columns={"Name"}), @ORM\UniqueConstraint(name="Id", columns={"Id"})}, indexes={@ORM\Index(name="FKOrganisati891792", columns={"ContactId"}), @ORM\Index(name="FKOrganisati829755", columns={"CreatorId"})})
 * @ORM\Entity
 */
class Organisation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="Id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="string", length=1000, nullable=false)
     */
    private $description;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CreatorId", referencedColumnName="Id")
     * })
     */
    private $creatorid;

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
     * Set creatorid
     *
     * @param \AppBundle\Entity\User $creatorid
     *
     * @return Organisation
     */
    public function setCreatorid(\AppBundle\Entity\User $creatorid = null)
    {
        $this->creatorid = $creatorid;

        return $this;
    }

    /**
     * Get creatorid
     *
     * @return \AppBundle\Entity\User
     */
    public function getCreatorid()
    {
        return $this->creatorid;
    }

    /**
     * Set contactid
     *
     * @param \AppBundle\Entity\Contact $contactid
     *
     * @return Organisation
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
