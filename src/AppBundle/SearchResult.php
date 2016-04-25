<?php

<<<<<<< HEAD
namespace AppBundle; 

class SearchResult
{ 
    private $class_name; 
    private $entity; 

    const MAX_CHARS = 150;

    /**
     * get array of SearchResult by extracting from entities
     * @param  Array $entities array of entities from AppBundle/Entity/*
     * @return Array           array of uniform SearchResult instances
     */
=======
namespace AppBundle;

class SearchResult
{
    private $title;
    private $body;
    private $link;

    const MAX_CHARS = 150;

>>>>>>> master
    public static function fromEntities($entities)
    {
        $results = array();
        foreach ($entities as $entity) {
            array_push($results, SearchResult::fromEntity($entity));
        }
        return $results;
    }

<<<<<<< HEAD
    /**
     * Converts one entity to a SearchResult
     * @param  mixed $entity an entity from AppBundle/Entity/*
     * @return SearchResult         a uniform SearchResult instance
     */
=======
>>>>>>> master
    public static function fromEntity($entity)
    {
        $class_path = get_class($entity);
        $class_name = explode( '\\', $class_path)[2];
<<<<<<< HEAD
        return new SearchResult($class_name, $entity);
    }

    private function __construct($class_name, $entity)
    {
        $this->class_name = $class_name;
        $this->entity = $entity;
    }

    /**
     * get the class name
     * @return string
     */
    public function getClass_name()
    {
        return $this->class_name;
    }

    /**
     * get the entity
     * @return entity
     */
    public function getEntity()
    {
        return $this->entity;
=======
        return SearchResult::{"create".$class_name."Result"}($entity);
    }

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

    private static function createOrganisationResult($org)
    {
        $title = $org->getName();
        $body = substr($org->getDescription()." some organisation body example"
            , 0, SearchResult::MAX_CHARS);
        $link = "/organisatie/".$org->getId();
        return new SearchResult($title, $body, $link);
    }

    private static function createVacancyResult($vac)
    {
        $title = $vac->getTitle();
        $body = substr($vac->getDescription()." some vacancy body example"
            , 0, SearchResult::MAX_CHARS);
        $link = "/vacature/".$vac->getId();
        return new SearchResult($title, $body, $link);
    }

    private function __construct($title, $body, $link)
    {
        $this->title = $title;
        $this->body = $body;
        $this->link = $link;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getLink()
    {
        return $this->link;
>>>>>>> master
    }
}
