<?php

namespace AppBundle\Entity;

class SearchFilter
{
    /**
     * @var integer
     */
    private $id;

    /**
     * The name for the search filter
     * @var string
     */
    private $name = '';

    /**
     * The search term
     * @var String
     */
    private $search = '';

    /**
     * Whether or not to search for the person document type
     * @var boolean
     */
    private $person = true;

    /**
     * Whether or not to search for the organisation document type.  Named $org instead of $organisation because of a mapping conflict in es (fields with the same name in different documents must have the same mappings.)
     * @var boolean
     */
    private $org = true;

    /**
     * Whether or not to search for the vacancy document type
     * @var boolean
     */
    private $vacancy = true;

    /**
     * How to sort the search results, there are 4 possible values: distance, date, enddate, reward
     * @var String
     */
    private $sort = '';

    /**
     * The sectors to filter on, in the case of an organisation.
     * @var \Doctrine\Common\Collections\Collection
     */
    private $sectors;

    /**
     * The categories to filter on, in the case of a person (interests) or vacancy (skills)
     * @var \Doctrine\Common\Collections\Collection
     */
    private $categories;

    /**
     * How long the commitment as a volunteer will last.  Currently there are 2 possibilities: '1time' and 'long'.
     * @var String
     */
    private $intensity = '';

    /**
     * The amount of hours / week the commitment tends to take.
     * @var integer
     */
    private $hoursAWeek = 0;

    /**
     * How far away the vacancy/organisation/person can be from the home-address
     * @var integer
     */
    private $distance = 0;

    /**
     * An additional characteristic to filter on, can at current be either weelchair-accessibility and the amount of social contact
     * @var Array (of strings)
     */
    private $characteristic = [];

    /**
     * Whether or not there's a renumeration, either money or another kind.
     * @var Array (of strings)
     */
    private $advantages = [];


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
     * @return SearchFilter
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return SearchFilter
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get search
     *
     * @return string
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * Set search
     *
     * @param string $search
     *
     * @return SearchFilter
     */
    public function setSearch($search)
    {
        $this->search = $search;

        return $this;
    }

    /**
     * Get person
     *
     * @return AppBundle\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set person
     *
     * @param AppBundle\Entity\Person $person
     *
     * @return SearchFilter
     */
    public function setPerson($person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get org
     *
     * @return AppBundle\Entity\Organisation
     */
    public function getOrg()
    {
        return $this->org;
    }

    /**
     * Set org
     *
     * @param AppBundle\Entity\Organisation $org
     *
     * @return SearchFilter
     */
    public function setOrg($org)
    {
        $this->org = $org;

        return $this;
    }

    /**
     * Get vacancy
     *
     * @return AppBundle\Entity\Vacancy
     */
    public function getVacancy()
    {
        return $this->vacancy;
    }

    /**
     * Set vacancy
     *
     * @param AppBundle\Entity\Vacancy $vacancy
     *
     * @return SearchFilter
     */
    public function setVacancy($vacancy)
    {
        $this->vacancy = $vacancy;

        return $this;
    }

    /**
     * Set sort
     *
     * @param string $sort
     *
     * @return SearchFilter
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return string
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Add sectors
     *
     * @param \AppBundle\Entity\Skill $sector
     *
     * @return SearchFilter
     */
    public function addSector(\AppBundle\Entity\Skill $sector)
    {
        $this->sectors[] = $sector;

        return $this;
    }

    /**
     * Remove sector
     *
     * @param \AppBundle\Entity\Skill $sector
     *
     * @return SearchFilter
     */
    public function removeSector(\AppBundle\Entity\Skill $sector)
    {
        $this->sectors->removeElement($sector);

        return $this;
    }

    /**
     * Get sectors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSectors()
    {
        return $this->sectors;
    }

    /**
     * Add categories
     *
     * @param \AppBundle\Entity\Skill $categorie
     *
     * @return SearchFilter
     */
    public function addCategories(\AppBundle\Entity\Skill $categorie)
    {
        $this->categories[] = $categorie;

        return $this;
    }

    /**
     * Remove categorie
     *
     * @param \AppBundle\Entity\Skill $categorie
     *
     * @return SearchFilter
     */
    public function removeCategories(\AppBundle\Entity\Skill $categorie)
    {
        $this->categories->removeElement($categorie);

        return $this;
    }

    /**
     * Get sectors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set intensity
     *
     * @param string $intensity
     *
     * @return SearchFilter
     */
    public function setIntensity($intensity)
    {
        $this->intensity = $intensity;

        return $this;
    }

    /**
     * Get intensity
     *
     * @return string
     */
    public function getIntensity()
    {
        return $this->intensity;
    }

    /**
     * Set hoursAWeek
     *
     * @param integer $hoursAWeek
     *
     * @return SearchFilter
     */
    public function setHoursAWeek($hoursAWeek)
    {
        $this->hoursAWeek = $hoursAWeek;

        return $this;
    }

    /**
     * Get hoursAWeek
     *
     * @return integer
     */
    public function getHoursAWeek()
    {
        return $this->hoursAWeek;
    }

    /**
     * Set distance
     *
     * @param integer $distance
     *
     * @return SearchFilter
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * Get distance
     *
     * @return integer
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * Set characteristic
     *
     * @param string $characteristic
     *
     * @return SearchFilter
     */
    public function setCharacteristic($characteristic)
    {
        $this->characteristic = $characteristic;

        return $this;
    }

    /**
     * Get characteristic
     *
     * @return string
     */
    public function getCharacteristic()
    {
        return $this->characteristic;
    }

    /**
     * Set advantages
     *
     * @param string $advantages
     *
     * @return SearchFilter
     */
    public function setAdvantages($advantages)
    {
        $this->advantages = $advantages;

        return $this;
    }

    /**
     * Get advantages
     *
     * @return string
     */
    public function getAdvantages()
    {
        return $this->advantages;
    }
}
