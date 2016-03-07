<?php

namespace AppBundle\Entity;

/**
 * Skillproficiency
 */
class Skillproficiency
{
    /**
     * @var integer
     */
    private $proficiency;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Skill
     */
    private $type;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $vacancy;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $volunteer;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->vacancy = new \Doctrine\Common\Collections\ArrayCollection();
        $this->volunteer = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set proficiency
     *
     * @param integer $proficiency
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
     * @return integer
     */
    public function getProficiency()
    {
        return $this->proficiency;
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

    /**
     * Add vacancy
     *
     * @param \AppBundle\Entity\Vacancy $vacancy
     *
     * @return Skillproficiency
     */
    public function addVacancy(\AppBundle\Entity\Vacancy $vacancy)
    {
        $this->vacancy[] = $vacancy;

        return $this;
    }

    /**
     * Remove vacancy
     *
     * @param \AppBundle\Entity\Vacancy $vacancy
     */
    public function removeVacancy(\AppBundle\Entity\Vacancy $vacancy)
    {
        $this->vacancy->removeElement($vacancy);
    }

    /**
     * Get vacancy
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVacancy()
    {
        return $this->vacancy;
    }

    /**
     * Add volunteer
     *
     * @param \AppBundle\Entity\Volunteer $volunteer
     *
     * @return Skillproficiency
     */
    public function addVolunteer(\AppBundle\Entity\Volunteer $volunteer)
    {
        $this->volunteer[] = $volunteer;

        return $this;
    }

    /**
     * Remove volunteer
     *
     * @param \AppBundle\Entity\Volunteer $volunteer
     */
    public function removeVolunteer(\AppBundle\Entity\Volunteer $volunteer)
    {
        $this->volunteer->removeElement($volunteer);
    }

    /**
     * Get volunteer
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVolunteer()
    {
        return $this->volunteer;
    }
}

