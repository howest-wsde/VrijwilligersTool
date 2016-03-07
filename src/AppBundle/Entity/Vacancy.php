<?php

namespace AppBundle\Entity;

/**
 * Vacancy
 */
class Vacancy
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \DateTime
     */
    private $startdate;

    /**
     * @var \DateTime
     */
    private $enddate;

    /**
     * @var \DateTime
     */
    private $creationtime;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Organisation
     */
    private $organisation;

    /**
     * @var \AppBundle\Entity\Vacancycategory
     */
    private $category;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $skillproficiency;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->skillproficiency = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Vacancy
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Vacancy
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set startdate
     *
     * @param \DateTime $startdate
     *
     * @return Vacancy
     */
    public function setStartdate($startdate)
    {
        $this->startdate = $startdate;

        return $this;
    }

    /**
     * Get startdate
     *
     * @return \DateTime
     */
    public function getStartdate()
    {
        return $this->startdate;
    }

    /**
     * Set enddate
     *
     * @param \DateTime $enddate
     *
     * @return Vacancy
     */
    public function setEnddate($enddate)
    {
        $this->enddate = $enddate;

        return $this;
    }

    /**
     * Get enddate
     *
     * @return \DateTime
     */
    public function getEnddate()
    {
        return $this->enddate;
    }

    /**
     * Set creationtime
     *
     * @param \DateTime $creationtime
     *
     * @return Vacancy
     */
    public function setCreationtime($creationtime)
    {
        $this->creationtime = $creationtime;

        return $this;
    }

    /**
     * Get creationtime
     *
     * @return \DateTime
     */
    public function getCreationtime()
    {
        return $this->creationtime;
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
     * Set organisation
     *
     * @param \AppBundle\Entity\Organisation $organisation
     *
     * @return Vacancy
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
     * Set category
     *
     * @param \AppBundle\Entity\Vacancycategory $category
     *
     * @return Vacancy
     */
    public function setCategory(\AppBundle\Entity\Vacancycategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Vacancycategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add skillproficiency
     *
     * @param \AppBundle\Entity\Skillproficiency $skillproficiency
     *
     * @return Vacancy
     */
    public function addSkillproficiency(\AppBundle\Entity\Skillproficiency $skillproficiency)
    {
        $this->skillproficiency[] = $skillproficiency;

        return $this;
    }

    /**
     * Remove skillproficiency
     *
     * @param \AppBundle\Entity\Skillproficiency $skillproficiency
     */
    public function removeSkillproficiency(\AppBundle\Entity\Skillproficiency $skillproficiency)
    {
        $this->skillproficiency->removeElement($skillproficiency);
    }

    /**
     * Get skillproficiency
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSkillproficiency()
    {
        return $this->skillproficiency;
    }
}

