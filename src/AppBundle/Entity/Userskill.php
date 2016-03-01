<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Userskill
 *
 * @ORM\Table(name="UserSkill", indexes={@ORM\Index(name="FKUserSkill759301", columns={"ProficiencyId"})})
 * @ORM\Entity
 */
class Userskill
{
    /**
     * @var integer
     *
     * @ORM\Column(name="Id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Skillproficiency
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Skillproficiency")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ProficiencyId", referencedColumnName="Id", unique=true)
     * })
     */
    private $proficiencyid;



    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Userskill
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
     * Set proficiencyid
     *
     * @param \AppBundle\Entity\Skillproficiency $proficiencyid
     *
     * @return Userskill
     */
    public function setProficiencyid(\AppBundle\Entity\Skillproficiency $proficiencyid = null)
    {
        $this->proficiencyid = $proficiencyid;

        return $this;
    }

    /**
     * Get proficiencyid
     *
     * @return \AppBundle\Entity\Skillproficiency
     */
    public function getProficiencyid()
    {
        return $this->proficiencyid;
    }
}
