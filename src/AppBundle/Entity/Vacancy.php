<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Vacancy
 */
class Vacancy
{
    /**
     * @var string
     * @Assert\NotBlank(message ="organisation.not_blank")
     * @Assert\Length(
     *      min = 4,
     *      max = 100,
     *      minMessage = "vacancy.min_message",
     *      maxMessage = "vacancy..max_message"
     * )
     * @Assert\NotEqualTo("nieuw")
    */
    private $title;

    /**
     * @var string
     * @Assert\NotBlank(message = "organisation.not_blank")
     * @Assert\Length(
     *      min = 20,
     *      max = 2000,
     *      minMessage = "vacancy.min_message",
     *      maxMessage = "vacancy.max_message"
     * )
    */
    private $description;

    /**
     * @var \Datetime
     * @Assert\Type(
     * 		type = "\DateTime",
     *      message = "vacancy.date.valid",
     * )
     * @Assert\GreaterThanOrEqual(
     * 		value = "today",
     * 		message = "vacancy.date.not_today"
     * )
     */
    private $startdate;

    /**
     * @var \Datetime
     * @Assert\Type(
     * 		type = "\DateTime",
     *      message = "vacancy.date.valid",
     * )
     * @Assert\GreaterThanOrEqual(
     * 		value = "today",
     * 		message = "vacancy.date.not_today"
     * )
     * @Assert\Expression(
     *     "this.getEnddate() >= this.getStartdate()",
     *     message = "vacancy.date.not_more_than"
     * )
     *
     * @Assert\Expression(
     *     "this.getEnddate() <= this.getStartdate().modify('+6 month')",
     *     message = "vacancy.date.max_period"
     * )
     */
    private $enddate;

    /**
     * @var integer
     */
    private $id;



    /**
     * @var \AppBundle\Entity\Organisation
     */
    private $organisation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $skills;

    /**
     * @var string
     */
    private $urlid;


    /**
     * Set urlId
     *
     * @param string $urlId
     *
     * @return Vacancy
     */
    public function setUrlId($urlId)
    {
        $this->urlid = $urlId;

        return $this;
    }

    /**
     * Get urlId
     *
     * @return string
     */
    public function getUrlId()
    {
        return $this->urlid;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->skills= new \Doctrine\Common\Collections\ArrayCollection();
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * @return Vacancy
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * Add skills
     *
     * @param \AppBundle\Entity\Skill $skill
     *
     * @return Vacancy
     */
    public function addSkill(\AppBundle\Entity\Skill $skill)
    {
        $this->skills[] = $skill;

        return $this;
    }

    /**
     * Remove skill
     *
     * @param \AppBundle\Entity\Skill $skill
     */
    public function removeSkill(\AppBundle\Entity\Skill $skill)
    {
        $this->skills->removeElement($skill);
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    function __toString()
    {
        $reflect = new \ReflectionClass($this);
        return json_encode( array(
            "Entity" => $reflect->getShortName(),
            "Id" => $this->getId(),
            "Values" => array(
                "Title" => $this->getTitle(),
                "Description" => $this->getDescription(),
                "Startdate" => $this->getStartdate(),
                "Enddate" => $this->getEnddate(),
                "Organisation" => $this->getOrganisation()
            )
        ));
    }

    /**
     * Get skills
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * Get the class name
     *
     * @return string
     */
    public function getClassName()
    {
        $reflect = new \ReflectionClass($this);
        return $reflect->getShortName();
    }
}
