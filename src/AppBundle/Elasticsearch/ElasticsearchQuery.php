<?php

namespace AppBundle\Elasticsearch;

use Elasticsearch\ClientBuilder;

class ElasticsearchQuery
{
    private $es_host;
    private $es_port;
    private $index;
    private $client;
    private $raw_result;

    public function create()
    {
        $es_instances = ["http://".$this->es_host.":".$this->es_port];
        $this->client = ClientBuilder::create()
            ->setHosts($es_instances)->build();
    }

    public function setIndex($index)
    {
        $this->index = $index;
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function setHost($host)
    {
        $this->es_host = $host;
    }

    public function setPort($port)
    {
        $this->es_port = $port;
    }

    private function isEntity($value)
    {
        return substr($value, 2, 6) == "Entity";
    }

    public function getRaw()
    {
        return $this->raw_result;
    }

    public function getResults()
    {
        return $this->getEntities();
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
     * Method to convert multiple ES hits into entities from this bundle.  It can handle nested entities as it checks each value to see whether it is an entity itself (using the isEntity() method from this class).  Hits don't need to be supplied as a param, as they are taken from the raw_result property of this class.  That means this method can only succesfully be called after using the search() method of this class.
     * Note: it cannot handle values that are an array itself.  Meaning nesting arrays is out of the question.
     * @return array    An array containing the mapped entities.
     */
    public function getEntities()
    {
        $entities = array();
        $hits = $this->raw_result["hits"]["hits"];
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

    /**
     * A search using the parameters given, this can be a single param or an array of params.
     * @param  array    $params     An array of search parameters
     * @return json               the raw search result
     */
    public function search($params)
    {
        $this->raw_result = $this->client->search($params);
        return $this->raw_result;
    }
}
