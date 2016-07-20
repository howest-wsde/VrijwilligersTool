<?php

namespace AppBundle\Entity;

/**
 * DigestEntry is an entity representing something that happened for which a mail needs to be sent in the daily/weekly/monthly chron job for mails => a digest entry.
 */
class DigestEntry extends EntityBase
{
    const NEWCHARGE = 1;
    const NEWVACANCY = 2;
    const NEWCANDIDATES = 3;
    const NEWADMIN = 4;
    const VACANCYFILLED = 5;

    const SENT = 'sent';
    const TBP = 'tbp'; //To be processed

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $status = DigestEntry::TBP;

    /**
     * @var integer
     */
    private $event;

    /**
     * @var integer
     */
    private $periodicity;

    /**
     * The user the cron job is for
     * @var \AppBundle\Entity\Person
     */
    private $user;

    /**
     * The user who's a candidate for new job
     * @var \AppBundle\Entity\Person
     */
    private $candidate;

    /**
     * The user who just got made into an admin
     * @var \AppBundle\Entity\Person
     */
    private $admin;

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
     * Set status
     *
     * @param String
     *
     * @return DigestEntry
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return String
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set event
     *
     * @param integer
     *
     * @return DigestEntry
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return integer
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set periodicity
     *
     * @param integer
     *
     * @return DigestEntry
     */
    public function setPeriodicity($periodicity)
    {
        $this->periodicity = $periodicity;

        return $this;
    }

    /**
     * Get periodicity
     *
     * @return integer
     */
    public function getPeriodicity()
    {
        return $this->periodicity;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\Person $user
     *
     * @return DigestEntry
     */
    public function setUser(\AppBundle\Entity\Person $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\Person
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set candidate
     *
     * @param \AppBundle\Entity\Person $candidate
     *
     * @return DigestEntry
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
     * Set admin
     *
     * @param \AppBundle\Entity\Person $admin
     *
     * @return DigestEntry
     */
    public function setAdmin(\AppBundle\Entity\Person $admin = null)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get admin
     *
     * @return \AppBundle\Entity\Person
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set vacancy
     *
     * @param \AppBundle\Entity\Vacancy $vacancy
     *
     * @return DigestEntry
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

    /**
     * Set organisation
     *
     * @param \AppBundle\Entity\Organisation $organisation
     *
     * @return DigestEntry
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
}
