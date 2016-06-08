<?php

namespace AppBundle\Entity\Form;

class SearchFilter
{
    private $search; //search term (named search for convenience in the form)
    private $person = true;
    private $organisation = true;
    private $vacancy = true;


    public function getSearch()
    {
        return $this->search;
    }

    public function setSearch($search)
    {
        $this->search = $search;

        return $this;
    }

    public function getPerson()
    {
        return $this->person;
    }

    public function setPerson($person)
    {
        $this->person = $person;

        return $this;
    }

    public function getOrganisation()
    {
        return $this->organisation;
    }

    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;

        return $this;
    }

    public function getVacancy()
    {
        return $this->vacancy;
    }

    public function setVacancy($vacancy)
    {
        $this->vacancy = $vacancy;

        return $this;
    }

}
