<?php

namespace AppBundle\Entity;

/**
 * Candidacy
 */
class Candidacy extends EntityBase
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $state;

    /**
     * @var \AppBundle\Entity\Person
     */
    private $person;

    /**
     * @var \AppBundle\Entity\Vacancy
     */
    private $vacancy;


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
     * Set state
     *
     * @param integer $state
     *
     * @return Candidacy
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return integer
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set person
     *
     * @param \AppBundle\Entity\Person $person
     *
     * @return Candidacy
     */
    public function setPerson(\AppBundle\Entity\Person $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \AppBundle\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set vacancy
     *
     * @param \AppBundle\Entity\Vacancy $vacancy
     *
     * @return Candidacy
     */
    public function setVacancy(\AppBundle\Entity\Vacancy $vacancy = null)
    {
        $this->vacancy = $vacancy;

        return $this;
    }

    /**
     * Get vacancy
     *
     * @return \AppBundle\Entity\Vacancy
     */
    public function getVacancy()
    {
        return $this->vacancy;
    }
}
