<?php

namespace AppBundle\Entity;

/**
 * Feedback
 */
class Feedback
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $reportdate;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $report;

    /**
     * @var integer
     */
    private $state;

    /**
     * @var \AppBundle\Entity\Person
     */
    private $reporter;


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
     * Set reportdate
     *
     * @param \DateTime $reportdate
     *
     * @return Feedback
     */
    public function setReportdate($reportdate)
    {
        $this->reportdate = $reportdate;

        return $this;
    }

    /**
     * Get reportdate
     *
     * @return \DateTime
     */
    public function getReportdate()
    {
        return $this->reportdate;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Feedback
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set report
     *
     * @param string $report
     *
     * @return Feedback
     */
    public function setReport($report)
    {
        $this->report = $report;

        return $this;
    }

    /**
     * Get report
     *
     * @return string
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * Set state
     *
     * @param integer $state
     *
     * @return Feedback
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
     * Set reporter
     *
     * @param \AppBundle\Entity\Person $reporter
     *
     * @return Feedback
     */
    public function setReporter(\AppBundle\Entity\Person $reporter = null)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * Get reporter
     *
     * @return \AppBundle\Entity\Person
     */
    public function getReporter()
    {
        return $this->reporter;
    }
}
