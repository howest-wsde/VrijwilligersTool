<?php

namespace AppBundle\Elasticsearch;

use Elasticsearch\ClientBuilder;
use AppBundle\SearchResult;

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

    private function jsonToEntity($json)
    {
        $json = json_decode($json, true);
        $classname = "AppBundle\Entity\\".$json["Entity"];
        $entity = new $classname();
        $entity->setId($json["Id"]);
        $values = $json["Values"];
        foreach ($values as $key => $value) {
            $entity->{"set".$key}($value);
        }
        return $entity;
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
                if ($this->isEntity($value))
                {
                    $value = $this->jsonToEntity($value);
                }
                $entity->{"set".$key}($value);
            }
            $id = $hits[$i]["_id"];
            $entity->setId($id);
            array_push($entities, $entity);
        }
        return $entities;
    }

    public function getRaw()
    {
        return $this->raw_result;
    }

    public function getSearchResults()
    {
        return SearchResult::fromEntities($this->getEntities());
    }
}
