<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Skillproficiency
 *
 * @ORM\Table(name="SkillProficiency", uniqueConstraints={@ORM\UniqueConstraint(name="Id", columns={"Id"})}, indexes={@ORM\Index(name="FKSkillProfi479319", columns={"Type"})})
 * @ORM\Entity
 */
class Skillproficiency
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
     * @var boolean
     *
     * @ORM\Column(name="Proficiency", type="boolean", nullable=false)
     */
    private $proficiency;

    /**
     * @var \AppBundle\Entity\Skill
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Skill")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Type", referencedColumnName="Id")
     * })
     */
    private $type;



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
     * Set proficiency
     *
     * @param boolean $proficiency
     *
     * @return Skillproficiency
     */
    public function setProficiency($proficiency)
    {
        $this->proficiency = $proficiency;

        return $this;
    }

    /**
     * Get proficiency
     *
     * @return boolean
     */
    public function getProficiency()
    {
        return $this->proficiency;
    }

    /**
     * Set type
     *
     * @param \AppBundle\Entity\Skill $type
     *
     * @return Skillproficiency
     */
    public function setType(\AppBundle\Entity\Skill $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \AppBundle\Entity\Skill
     */
    public function getType()
    {
        return $this->type;
    }
}
