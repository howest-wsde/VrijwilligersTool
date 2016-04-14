<?php

namespace AppBundle;

class SearchResult
{
    private $title;
    private $body;
    private $link;

    const MAX_CHARS = 150;

    /**
     * get array of SearchResult by extracting from entities
     * @param  Array $entities array of entities from AppBundle/Entity/*
     * @return Array           array of uniform SearchResult instances
     */
    public static function fromEntities($entities)
    {
        $results = array();
        foreach ($entities as $entity) {
            array_push($results, SearchResult::fromEntity($entity));
        }
        return $results;
    }

    /**
     * Converts one entity to a SearchResult
     * @param  mixed $entity an entity from AppBundle/Entity/*
     * @return SearchResult         a uniform SearchResult instance
     */
    public static function fromEntity($entity)
    {
        $class_path = get_class($entity);
        $class_name = explode( '\\', $class_path)[2];
        return SearchResult::{"create".$class_name."Result"}($entity);
    }

    /**
     * Creates a SearchResult from a Volunteer
     * @param  volunteer $vol an instance of Volunteer
     * @return SearchResult      a uniform SearchResult instace
     */
    private static function createVolunteerResult($vol)
    {
        $title = $vol->getFullName();
        if ($vol->getUsername())
        {
            $title .=" ( ".$vol->getUsername()." )";
        }
        $body = substr($vol->getEmail()." some volunteer body examples"
            , 0, SearchResult::MAX_CHARS); // skills
        $link = "/persoon/".$vol->getId();
        return new SearchResult($title, $body, $link);
    }

    /**
     * Creates a SearchResult from an Organisation
     * @param  Organisation $vol an instance of Organisation
     * @return SearchResult      a uniform SearchResult instace
     */
    private static function createOrganisationResult($org)
    {
        $title = $org->getName();
        $body = substr($org->getDescription()." some organisation body example"
            , 0, SearchResult::MAX_CHARS);
        $link = "/organisatie/".$org->getId();
        return new SearchResult($title, $body, $link);
    }

    /**
     * Creates a SearchResult from a Vacancy
     * @param  Vacancy $vol an instance of Vacancy
     * @return SearchResult      a uniform SearchResult instace
     */
    private static function createVacancyResult($vac)
    {
        $title = $vac->getTitle();
        $body = substr($vac->getDescription()." some vacancy body example"
            , 0, SearchResult::MAX_CHARS);
        $link = "/vacature/".$vac->getId();
        return new SearchResult($title, $body, $link);
    }

    /**
     * Creates an instance of SearchResult
     * @param string $title title
     * @param string $body  body
     * @param string $link  link
     */
    private function __construct($title, $body, $link)
    {
        $this->title = $title;
        $this->body = $body;
        $this->link = $link;
    }

    /**
     * get the title
     * @return string the title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * get the body
     * @return string the body
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * get the link
     * @return string the link
     */
    public function getLink()
    {
        return $this->link;
    }
}
