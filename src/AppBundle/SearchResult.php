<?php

namespace AppBundle;

class SearchResult
{
    private $title;
    private $body;
    private $link;

    const MAX_CHARS = 150;

    public static function fromEntities($entities)
    {
        $results = array();
        foreach ($entities as $entity) {
            array_push($results, SearchResult::fromEntity($entity));
        }
        return $results;
    }

    public static function fromEntity($entity)
    {
        $class_path = get_class($entity);
        $class_name = explode( '\\', $class_path)[2];
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
    }
}
