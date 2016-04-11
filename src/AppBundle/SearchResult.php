<?php

namespace AppBundle;

class SearchResult
{
    private $title;
    private $body;
    private $link;

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
        $body = $vol->getEmail()." "."some body examples"; // skills
        $link = "/person/".$vol->getId();
        return new SearchResult($title, $body, $link);
    }

    private static function createOrganisationResult($contact)
    {
        // TODO: implement
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
