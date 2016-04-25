<?php

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
    }
}
