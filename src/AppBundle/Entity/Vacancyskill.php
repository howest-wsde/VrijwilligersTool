<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vacancyskill
 *
 * @ORM\Table(name="VacancySkill", indexes={@ORM\Index(name="FKVacancySki10694", columns={"ProficiencyId"})})
 * @ORM\Entity
 */
class Vacancyskill
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
     * @return Vacancyskill
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
     * @return Vacancyskill
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
