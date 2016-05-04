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
    private $candidate;

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
     * Set candidate
     *
     * @param \AppBundle\Entity\Person $candidate
     *
     * @return Candidacy
     */
    public function setCandidate(\AppBundle\Entity\Person $candidate = null)
    {
        $this->candidate = $candidate;

        return $this;
    }

    /**
     * Get candidate
     *
     * @return \AppBundle\Entity\Person
     */
    public function getCandidate()
    {
        return $this->candidate;
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
