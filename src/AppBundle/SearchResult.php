<?php

namespace AppBundle; 

class SearchResult
{ 
    private $class_name; 
    private $entity; 

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
        return new SearchResult($class_name, $entity);
    }

    private function __construct($class_name, $entity)
    {
        $this->class_name = $class_name;
        $this->entity = $entity;
    }
 
    public function getClass_name()
    {
        return $this->class_name;
    }

    public function getEntity()
    {
        return $this->entity;
    }
}
