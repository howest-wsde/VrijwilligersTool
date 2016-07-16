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

    /**
     * Method to convert multiple ES hits into entities from this bundle.  The method uses the getEntities method of an ESMapper instance.  It can handle nested entities.  Hits don't need to be supplied as a param, as they are taken from the raw_result property of this class.  That means this method can only succesfully be called after using the search() method of this class.
     * Note: it cannot handle values that are an array itself.  Meaning nesting arrays is out of the question.
     * @return array    An array containing the mapped entities.
     */
    public function getEntities()
    {
        return $this->esMapper->getEntities($this->raw_result["hits"]["hits"]);
    }

    /**
     * A search using the parameters given, this can be a single param or an array of params.
     * @param  array    $params     An array of search parameters
     * @return json                 The raw search result
     */
    public function search($params)
    {
        $this->raw_result = $this->client->search($params);
        return $this->raw_result;
    }

    /**
     * Convenience method bundling the search and getEntities method together.  Meaning you can pass the search params and get the returned entities as a result.
     * @param  array    $params     An array of search parameters
     * @return array                An array containing the mapped entities.
     */
    public function searchForEntities($params)
    {
        $this->raw_result = $this->client->search($params);
        return $this->esMapper->getEntities($this->raw_result["hits"]["hits"]);
    }
}
