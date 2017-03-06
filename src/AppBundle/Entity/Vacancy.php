<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Vacancy
 */
class Vacancy extends EntityBase
{
    const OPEN = 1;
    const CLOSED = 2;
    const SAVED = 3;
    const DELETED = 4;

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
     * Whether or not a vacancy is weelchair-accessible
     * @var bool
     */
    private $access = false;

    /**
     * @var int
     */
    private $estimatedWorkInHours = 0;

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
     * @var string
     * @Assert\Length(
     *      max = 6,
     *      maxMessage = "vacancy.max_message",
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
     */
    protected $latitude;

    /**
     * @var string
     */
    protected $longitude;

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
    private $renumeration = 0;

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
     * @var Organisation
     */
    private $organisation;

    /**
     * @var Collection
     */
    private $skills;

    /**
     * @var Collection
     */
    private $candidacies;

    /**
     * @var string
     */
    private $urlid = "";

    /**
     * @var Collection
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
        $this->skills= new ArrayCollection();
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
     * @param $id
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
     * Set accessible
     *
     * @param bool $accessible
     *
     * @return Vacancy
     */
    public function setAccess($accessible)
    {
        $this->access = $accessible;

        return $this;
    }

    /**
     * Get accessible
     *
     * @return bool
     */
    public function getAccess()
    {
        return $this->access;
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
     * Convenience function to both increase stillWanted by one
     * & check whether it's status needs to opened again
     *
     * @return Vacancy
     */
    public function increaseByOne(){
        $left = $this->getStillWanted();
        if($left == 0){
            $this->setPublished(Vacancy::OPEN);
        }
        $this->setStillWanted($left + 1);

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
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return \int
     */
    public function getPostalCode()
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
     * Set lat
     *
     * @param string $lat
     *
     * @return Person
     */
    public function setLatitude($lat)
    {
        $this->latitude = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set long
     *
     * @param string $long
     *
     * @return Person
     */
    public function setLongitude($long)
    {
        $this->longitude = $long;

        return $this;
    }

    /**
     * Get long
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set organisation
     *
     * @param Organisation $organisation
     *
     * @return Vacancy
     */
    public function setOrganisation(Organisation $organisation = null)
    {
        $this->organisation = $organisation;

        return $this;
    }

    /**
     * Get organisation
     *
     * @return Organisation
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * Add skills
     *
     * @param Skill $skill
     *
     * @return Vacancy
     */
    public function addSkill(Skill $skill)
    {
        $this->skills[] = $skill;

        return $this;
    }

    /**
     * Remove skill
     *
     * @param Skill $skill
     */
    public function removeSkill(Skill $skill)
    {
        $this->skills->removeElement($skill);
    }

    /**
     * Add candidacy
     *
     * @param Candidacy $candidacy
     *
     * @return Person
     */
    public function addCandidacy(Candidacy $candidacy)
    {
        $this->candidacies[] = $candidacy;

        return $this;
    }

    /**
     * Remove candidacy
     *
      * @param Candidacy $candidacy
     *
     * @return Person
     */
    public function removeCandidacy(Candidacy $candidacy)
    {
        $this->candidacies->removeElement($candidacy);

        return $this;
    }

    /**
     * Get candidacies
     *
     * @return Collection
     */
    public function getCandidacies()
    {
        return $this->candidacies;
    }


    /**
     * Get pending candidates
     *
     * @return array
     */
    public function getCandidates()
    {
        $candidates = [];
        $openCandidacies = $this->getCandidacies()->filter(
            function($candidacy){
               return ($candidacy->getState() === Candidacy::PENDING);
        });

        foreach ($openCandidacies as $candidacy){
            $candidates[] = $candidacy->getCandidate();
        }

        return $candidates;
    }

    /**
     * Get active volunteers
     *
     * @return array
     */
    public function getVolunteers()
    {
        $volunteers = [];
        $openCandidacies = $this->getCandidacies()->filter(
            function($candidacy){
               return ($candidacy->getState() === Candidacy::APPROVED);
        });

        foreach ($openCandidacies as $candidacy){
            $volunteers[] = $candidacy->getCandidate();
        }

        return $volunteers;
    }

    /**
     * Add liker
     *
     * @param Person $liker
     *
     * @return Vacancy
     */
    public function addLiker(Person $liker)
    {
        $this->likers[] = $liker;

        return $this;
    }

    /**
     * Remove liker
     *
     * @param Person $liker
     */
    public function removeLiker(Person $liker)
    {
        $this->likers->removeElement($liker);
    }

    /**
     * Get likers
     *
     * @return Collection
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
     * @return Collection
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * Set skills
     * @param Collection $skills collection of skills
     * @return Vacancy
     */
    public function setSkills(Collection $skills)
    {
        $this->skills = $skills;
        return $this;
    }

    /**
     * Getter for a full address in string form, like so:
     * 'Koning Alberstraat 12, 9900 Eeklo'
     */
    public function getAddress()
    {
        return $this->getStreet() . ' '
               . $this->getNumber() . ', '
               . $this->getCity() . ' '
               . $this->getPostalCode();
    }

    /**
     * Return latitude and longitude in the correct format for ES
     * @return string string formatted as lat, long
     */
    public function esGetLocation()
    {
        $lat = $this->getLatitude();
        $long = $this->getLongitude();

        if($lat && $long){
            return $this->getLatitude() . ', ' . $this->getLongitude();
        }

        return null;
    }

    /**
     * Get the number of likers for a vacancy
     * @return int
     */
    public function esGetNumberOfLikers()
    {
        if (!is_null($this->getLikers())) {
            return $this->getLikers()->count();
        } else return 0;
    }

    /**
     * helper function to enable the entity property in nested objects within ES documents.  The helper property simply contains the name of the object type (in other words: the class name)
     * @return String the classname of this entity
     */
    public function esGetEntityName()
    {
        return 'vacancy';
    }

    /**
     * helper function to enable the entity property in nested objects within ES documents.  The helper property simply contains the string "nomap" to avoid it being mapped further
     * @return String the classname of this entity
     */
    public function esGetNoMap()
    {
        return 'nomap';
    }
    /**
     * @var \AppBundle\Entity\Person
     */
    private $creator;


    /**
     * Set creator
     *
     * @param \AppBundle\Entity\Person $creator
     *
     * @return Vacancy
     */
    public function setCreator(\AppBundle\Entity\Person $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \AppBundle\Entity\Person
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Add alert
     *
     * @param \AppBundle\Entity\Person $alert
     *
     * @return Vacancy
     */
    public function addAlert(\AppBundle\Entity\Person $alert)
    {
        $this->alerts[] = $alert;

        return $this;
    }

    /**
     * Remove alert
     *
     * @param \AppBundle\Entity\Person $alert
     */
    public function removeAlert(\AppBundle\Entity\Person $alert)
    {
        $this->alerts->removeElement($alert);
    }

    /**
     * Get alerts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAlerts()
    {
        return $this->alerts;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $alerts;

    /**
     * @return bool whether the entity should persist to Elasticsearch
     * Is used in elasticsearch.yml config file
     */
    public function shouldPersistToElasticsearch()
    {
        $isPublished = $this->getPublished();
        return  $isPublished != Vacancy::CLOSED and
                $isPublished != Vacancy::SAVED and
                $isPublished != Vacancy::DELETED;
    }
}
