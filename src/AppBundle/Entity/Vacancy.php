<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Vacancy
 */
class Vacancy extends EntityBase
{
    const OPEN = 1;
    const CLOSED = 2;
    const SAVED = 3;

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
     * @Assert\Length(
     *      min = 10,
     *      max = 120,
     *      minMessage = "vacancy.min_message",
     *      maxMessage = "vacancy.max_message"
     * )
    */
    private $summary;

    /**
     * @var string
     * @Assert\NotBlank(message = "vacancy.not_blank")
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
     *      type = "\DateTime",
     *      message = "vacancy.date.valid",
     * )
     * @Assert\GreaterThanOrEqual(
     *      value = "today",
     *      message = "vacancy.date.not_today"
     * )
     */
    private $startdate;

    /**
     * @var \Datetime
     * @Assert\Type(
     *      type = "\DateTime",
     *      message = "vacancy.date.valid",
     * )
     * @Assert\GreaterThanOrEqual(
     *      value = "today",
     *      message = "vacancy.date.not_today"
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
     * @var bool
     */
    private $longterm = false;

    /**
     * @var int
     */
    private $estimatedWorkInHours;

    /**
     * @var string
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "vacancy.max_message"
     * )
     */
    private $street;

    /**
     * @var int
     * @Assert\Regex(
     *     pattern = "/^[0-9]*$/",
     *     message = "vacancy.not_numeric"
     * )
     * @Assert\Range(
     *      min = 0,
     *      max = 999999,
     *      minMessage = "vacancy.not_positive"
     * )
     */
    private $number;

    /**
     * @var int
     * @Assert\Length(
     *      min = 1,
     *      max = 6,
     *      minMessage = "vacancy.min_message_one",
     *      maxMessage = "vacancy.max_message"
     * )
     * @Assert\Regex(
     *     pattern = "/^[a-zA-Z0-9]{1,6}$/",
     *     message = "vacancy.bus.valid"
     * )
     */
    private $bus;

    /**
     * @var int
     * @Assert\Regex(
     *     pattern = "/^[0-9]*$/",
     *     message = "vacancy.not_numeric"
     * )
     * @Assert\Range(
     *      min = 1000,
     *      max = 9999,
     *      minMessage = "vacancy.not_positive",
     *      maxMessage = "vacancy.not_more_than"
     * )
     * @Assert\Length(
     *      min = 4,
     *      max = 4,
     *      exactMessage = "vacancy.exact"
     * )
     */
    private $postalCode;

    /**
     * @var string
     * @Assert\Length(
     *      min = 1,
     *      max = 100,
     *      minMessage = "vacancy.min_message",
     *      maxMessage = "vacancy.max_message"
     * )
     */
    private $city;

    /**
     * @var string
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "vacancy.max_message"
     * )
     */
    private $socialInteraction = "normal";

    /**
     * @var string
     * @Assert\Length(
     *      max = 11,
     *      maxMessage = "vacancy.max_message"
     * )
     */
    private $independent;

    /**
     * @var float
     */
    private $renumeration;

    /**
     * @var string
     * @Assert\Length(
     *      max = 200,
     *      maxMessage = "vacancy.max_message"
     * )
     */
    private $otherReward;

    /**
     * @var int
     */
    private $published = Vacancy::OPEN;

    /**
     * @var int
     */
    private $wanted = 1;

    /**
     * @var int
     */
    private $stillWanted = 1;

    /**
     * @var \AppBundle\Entity\Organisation
     */
    private $organisation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $skills;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $candidacies;

    /**
     * @var string
     */
    private $urlid = "";

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $likers;

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
     * Set summary
     *
     * @param string $summary
     *
     * @return Vacancy
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
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
     * Set longterm
     *
     * @param bool $longterm
     *
     * @return Vacancy
     */
    public function setLongterm($longterm)
    {
        $this->longterm = $longterm;

        return $this;
    }

    /**
     * Get longterm
     *
     * @return bool
     */
    public function getLongterm()
    {
        return $this->longterm;
    }

    /**
     * Set estimatedWorkInHours
     *
     * @param int $estimatedWorkInHours
     *
     * @return Vacancy
     */
    public function setEstimatedWorkInHours($estimatedWorkInHours)
    {
        $this->estimatedWorkInHours = $estimatedWorkInHours;

        return $this;
    }

    /**
     * Get estimatedWorkInHours
     *
     * @return int
     */
    public function getEstimatedWorkInHours()
    {
        return $this->estimatedWorkInHours;
    }

    /**
     * Set socialInteraction
     *
     * @param string $socialInteraction
     *
     * @return Vacancy
     */
    public function setSocialInteraction($socialInteraction)
    {
        $this->socialInteraction = $socialInteraction;

        return $this;
    }

    /**
     * Get socialInteraction
     *
     * @return string
     */
    public function getSocialInteraction()
    {
        return $this->socialInteraction;
    }

    /**
     * Set independent
     *
     * @param string $independent
     *
     * @return Vacancy
     */
    public function setIndependent($independent)
    {
        $this->independent = $independent;

        return $this;
    }

    /**
     * Get independent
     *
     * @return string
     */
    public function getIndependent()
    {
        return $this->independent;
    }

    /**
     * Set renumeration
     *
     * @param float $renumeration
     *
     * @return Vacancy
     */
    public function setRenumeration($renumeration)
    {
        $this->renumeration = $renumeration;

        return $this;
    }

    /**
     * Get renumeration
     *
     * @return float
     */
    public function getRenumeration()
    {
        return $this->renumeration;
    }

    /**
     * Set otherReward
     *
     * @param string $otherReward
     *
     * @return Vacancy
     */
    public function setOtherReward($otherReward)
    {
        $this->otherReward = $otherReward;

        return $this;
    }

    /**
     * Get otherReward
     *
     * @return string
     */
    public function getOtherReward()
    {
        return $this->otherReward;
    }

    /**
     * Set published
     *
     * @param int $published
     *
     * @return Vacancy
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return int
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set wanted
     *
     * @param int $wanted
     *
     * @return Vacancy
     */
    public function setWanted($wanted)
    {
        $this->stillWanted += ($wanted - $this->wanted);
        $this->wanted = $wanted;
        return $this;
    }

    /**
     * Get wanted
     *
     * @return int
     */
    public function getWanted()
    {
        return $this->wanted;
    }

    /**
     * Set stillWanted
     *
     * @param int $stillWanted
     *
     * @return Vacancy
     */
    public function setStillWanted($stillWanted)
    {
        $this->stillWanted = $stillWanted;

        return $this;
    }

    /**
     * Get stillWanted
     *
     * @return int
     */
    public function getStillWanted()
    {
        return $this->stillWanted;
    }

    /**
     * Convenience function to both reduce stillWanted by one
     * & check whether it's status needs to be closed
     *
     * @return Vacancy
     */
    public function reduceByOne(){
        $left = $this->getStillWanted();
        if($left == 1){
            $this->setPublished(Vacancy::CLOSED);
        }
        $this->setStillWanted($left - 1);

        return $this;
    }

    /**
     * Set street
     *
     * @param string $street
     *
     * @return Organisation
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set number
     *
     * @param \int $number
     *
     * @return Organisation
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return \int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set postalCode
     *
     * @param \int $postalCode
     *
     * @return Organisation
     */
    public function setpostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return \int
     */
    public function getpostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set bus
     *
     * @param \int $bus
     *
     * @return Organisation
     */
    public function setBus($bus)
    {
        $this->bus = $bus;

        return $this;
    }

    /**
     * Get bus
     *
     * @return \int
     */
    public function getBus()
    {
        return $this->bus;
    }


    /**
     * Set city
     *
     * @param string $city
     *
     * @return Organisation
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
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
     * Add candidacy
     *
     * @param \AppBundle\Entity\Candidacy $candidacy
     *
     * @return Person
     */
    public function addCandidacy(\AppBundle\Entity\Candidacy $candidacy)
    {
        $this->candidacies[] = $candidacy;

        return $this;
    }

    /**
     * Remove candidacy
     *
      * @param \AppBundle\Entity\Candidacy $candidacy
     *
     * @return Person
     */
    public function removeCandidacy(\AppBundle\Entity\Candidacy $candidacy)
    {
        $this->candidacies->removeElement($candidacy);

        return $this;
    }

    /**
     * Get candidacies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCandidacies()
    {
        return $this->candidacies;
    }


    /**
     * Get candidates
     *
     * @return Array
     */
    public function getCandidates()
    {
        $arCandidates = [];
        foreach ($this->candidacies as $oCandidacy){
            $arCandidates[] = $oCandidacy->getCandidate();
        }
        return $arCandidates;
    }

    /**
     * Add liker
     *
     * @param \AppBundle\Entity\Person $liker
     *
     * @return Vacancy
     */
    public function addLiker(\AppBundle\Entity\Person $liker)
    {
        $this->likers[] = $liker;

        return $this;
    }

    /**
     * Remove liker
     *
     * @param \AppBundle\Entity\Person $liker
     */
    public function removeLiker(\AppBundle\Entity\Person $liker)
    {
        $this->likers->removeElement($liker);
    }

    /**
     * Get likers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLikers()
    {
        return $this->likers;
    }


    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    function __toString()
    {
        return json_encode( array(
            "Entity" => $this->getClassName(),
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
}
