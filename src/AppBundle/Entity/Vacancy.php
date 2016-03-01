<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vacancy
 *
 * @ORM\Table(name="Vacancy", uniqueConstraints={@ORM\UniqueConstraint(name="Id", columns={"Id"})}, indexes={@ORM\Index(name="FKVacancy396991", columns={"OrganisationId"})})
 * @ORM\Entity
 */
class Vacancy
{
    /**
     * @var integer
     *
     * @ORM\Column(name="Id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Title", type="string", length=150, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="string", length=2000, nullable=false)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="StartDate", type="datetime", nullable=true)
     */
    private $startdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="EndDate", type="datetime", nullable=true)
     */
    private $enddate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreationTime", type="datetime", nullable=true)
     */
    private $creationtime;

    /**
     * @var integer
     *
     * @ORM\Column(name="SkillId", type="integer", nullable=true)
     */
    private $skillid;

    /**
     * @var \AppBundle\Entity\Organisation
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Organisation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="OrganisationId", referencedColumnName="Id")
     * })
     */
    private $organisationid;



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
     * Set skillid
     *
     * @param integer $skillid
     *
     * @return Vacancy
     */
    public function setSkillid($skillid)
    {
        $this->skillid = $skillid;

        return $this;
    }

    /**
     * Get skillid
     *
     * @return integer
     */
    public function getSkillid()
    {
        return $this->skillid;
    }

    /**
     * Set organisationid
     *
     * @param \AppBundle\Entity\Organisation $organisationid
     *
     * @return Vacancy
     */
    public function setOrganisationid(\AppBundle\Entity\Organisation $organisationid = null)
    {
        $this->organisationid = $organisationid;

        return $this;
    }

    /**
     * Get organisationid
     *
     * @return \AppBundle\Entity\Organisation
     */
    public function getOrganisationid()
    {
        return $this->organisationid;
    }
}
