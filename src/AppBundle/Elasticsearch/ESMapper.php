<?php

namespace AppBundle\Elasticsearch;

/**
 * Class containing all logic to map an es search result in json format to the corresponding entities.
 */
class ESMapper{
    /**
     * Determines whether a value is an entity by checking for the presence of the string "Entity".
     * @param  String   $value      part of a json string constituting a value
     * @return boolean              true or false
     */
    private function isEntity($value)
    {
        return substr($value, 2, 6) == "Entity";
    }

    /**
     * Dynamically converts a json string to an entity in the same bundle.  It cannot handle nested entities @ this time.
     * @param  json     $json   a json-string
     * @return dynamic          the entity mapped from the json
     */
    private function jsonToEntity($json)
    {
        $json = json_decode($json, true);
        $classname = "AppBundle\Entity\\".$json["Entity"];
        $entity = new $classname();
        $entity->setId($json["Id"]);
        $values = $json["Values"]; //TODO check why this is extracted into a seperate var
        foreach ($values as $key => $value) {
            if (!is_null($value))
            {
                $entity->{"set".$key}($value);
            }
        }
        return $entity;
    }

    /**
     * Method to convert multiple ES hits into entities from this bundle.  It can handle nested entities as it checks each value to see whether it is an entity itself (using the isEntity() method from this class).
     * Note: it cannot handle values that are an array itself.  Meaning nesting arrays is out of the question.
     * @param json      $hits   A json string representing multiple entities.
     * @return array            An array containing the mapped entities.
     */
    public function getEntities($hits)
    {
        $entities = array();
        for ($i=0; $i < count($hits); $i++)
        {
            $source = $hits[$i]["_source"];
            $classname = "AppBundle\Entity\\".ucfirst($hits[$i]["_type"]);
            $entity = new $classname();
            foreach ($source as $key => $value) {
                if (!is_null($value) && !is_array($value))
                {
                    if ($this->isEntity($value))
                    {
                        $value = $this->jsonToEntity($value);
                    }
                    $entity->{"set".$key}($value);
                }
            }
            $id = $hits[$i]["_id"];
            $entity->setId($id);
            array_push($entities, $entity);
        }
        return $entities;
    }
}