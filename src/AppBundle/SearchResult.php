<?php

<<<<<<< HEAD
namespace AppBundle; 
=======
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
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613

class SearchResult
{ 
    private $class_name; 
    private $entity; 

    const MAX_CHARS = 150;

<<<<<<< HEAD
    /**
     * get array of SearchResult by extracting from entities
     * @param  Array $entities array of entities from AppBundle/Entity/*
     * @return Array           array of uniform SearchResult instances
     */
=======
>>>>>>> master
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
    public static function fromEntities($entities)
    {
        $results = array();
        foreach ($entities as $entity) {
            array_push($results, SearchResult::fromEntity($entity));
        }
        return $results;
    }

<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
    /**
     * Converts one entity to a SearchResult
     * @param  mixed $entity an entity from AppBundle/Entity/*
     * @return SearchResult         a uniform SearchResult instance
     */
<<<<<<< HEAD
=======
=======
>>>>>>> master
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
    public static function fromEntity($entity)
    {
        $class_path = get_class($entity);
        $class_name = explode( '\\', $class_path)[2];
<<<<<<< HEAD
        return new SearchResult($class_name, $entity);
=======
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
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
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
<<<<<<< HEAD
        return $this->entity;
=======
        return $this->link;
>>>>>>> master
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
    }
}
