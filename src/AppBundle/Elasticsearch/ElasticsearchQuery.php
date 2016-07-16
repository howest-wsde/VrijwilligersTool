<?php

namespace AppBundle\Elasticsearch;

use Elasticsearch\ClientBuilder;
use AppBundle\Elasticsearch\ESMapper;

class ElasticsearchQuery
{
    private $es_host;
    private $es_port;
    private $index;
    private $client;
    private $raw_result;
    private $esMapper;

    public function create()
    {
        $es_instances = ["http://".$this->es_host.":".$this->es_port];
        $this->client = ClientBuilder::create()
            ->setHosts($es_instances)->build();
        $this->esMapper = new ESMapper();
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

    public function getRaw()
    {
        return $this->raw_result;
    }

    public function getResults()
    {
        return $this->getEntities();
    }

    public function getEntities()
    {
        return $this->esMapper->getEntities($this->raw_result["hits"]["hits"]);
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
