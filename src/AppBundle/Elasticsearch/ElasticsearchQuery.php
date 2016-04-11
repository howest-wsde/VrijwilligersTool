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

    public function search($params)
    {
        $this->raw_result = $this->client->search($params);
        return $this->raw_result;
    }

    public function getEntities()
    {
        $entities = array();

        $hits = $this->raw_result["hits"]["hits"];
        $count = $this->raw_result["hits"]["total"];
        for ($i=0; $i < $count; $i++) {
            $source = $hits[$i]["_source"];
            $classname = "AppBundle\Entity\\".ucfirst($hits[$i]["_type"]);
            $entity = new $classname();
            foreach ($source as $key => $value) {
                $entity->{"set".$key}($value);
            }
            $id = $hits[$i]["_id"];
            $entity->setId($id);
            array_push($entities, $entity);
        }
        return $entities;
    }
}
