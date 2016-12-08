<?php

namespace AppBundle\Entity;

/**
 * AlertSetting
 */
class AlertSetting
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $state = 0;

    /**
     * @var \AppBundle\Entity\Person
     */
    private $person;

    /**
     * @var \AppBundle\Entity\Organisation
     */
    private $organisation;

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
     * @return AlertSetting
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
     * @return AlertSetting
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
     * Set organisation
     *
     * @param \AppBundle\Entity\Organisation $organisation
     *
     * @return AlertSetting
     */
    public function setOrganisation(\AppBundle\Entity\Organisation $organisation = null)
    {
        $this->organisation = $organisation;

        return $this;
    }

    /**
     * Get organisation
     *
     * @return \AppBundle\Entity\Organisation
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * Set vacancy
     *
     * @param \AppBundle\Entity\Vacancy $vacancy
     *
     * @return AlertSetting
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

