<?php

namespace AppBundle\Entity;

/**
 * Candidacy
 */
class Candidacy extends EntityBase
{
    const PENDING = 0;
    const APPROVED = 1;
    const DECLINED = 2;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $state = Candidacy::PENDING;

    /**
     * @var Person
     */
    private $candidate;

    /**
     * @var Vacancy
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
     * @param Person $candidate
     *
     * @return Candidacy
     */
    public function setCandidate(Person $candidate = null)
    {
        $this->candidate = $candidate;

        return $this;
    }

    /**
     * Get candidate
     *
     * @return Person
     */
    public function getCandidate()
    {
        return $this->candidate;
    }

    /**
     * Set vacancy
     *
     * @param Vacancy $vacancy
     *
     * @return Candidacy
     */
    public function setVacancy(Vacancy $vacancy = null)
    {
        $this->vacancy = $vacancy;

        return $this;
    }

    /**
     * Get vacancy
     *
     * @return Vacancy
     */
    public function getVacancy()
    {
        return $this->vacancy;
    }

    /**
     * helper function to enable the entity property in nested objects within ES documents.  The helper property simply contains the name of the object type (in other words: the class name)
     * @return String the classname of this entity
     */
    public function esGetEntityName()
    {
        return 'candidacy';
    }

    /**
     * helper function to enable the entity property in nested objects within ES documents.  The helper property simply contains the string "nomap" to avoid it being mapped further
     * @return String the classname of this entity
     */
    public function esGetNoMap()
    {
        return 'nomap';
    }
}
