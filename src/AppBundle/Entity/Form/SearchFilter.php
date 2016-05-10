<?php

namespace AppBundle\Entity\Form;

class SearchFilter
{
    private $term;
    private $person = true;
    private $organisation = true;
    private $vacancy = true;

    public function getTerm()
    {
        return $this->term;
    }

    public function setTerm($term)
    {
        $this->term = $term;

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
