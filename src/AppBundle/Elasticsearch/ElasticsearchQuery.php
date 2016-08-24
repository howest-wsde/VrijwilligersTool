<?php

namespace AppBundle\Elasticsearch;

use Elastica\Request;
use Elastica\Client;
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
    public function searchForRaw($params)
    {
        return $this->client->search($params);
    }

    /**
     * Convenience method bundling the searchForRaw and getEntities method together.  Meaning you can pass the search params and get the returned entities as a result.
     * @param  array    $params     An array of search parameters
     * @return array                An array containing the mapped entities.
     */
    public function search($params)
    {
        $this->raw_result = $this->client->search($params);
        return $this->esMapper->getEntities($this->raw_result["hits"]["hits"]);
    }

    /**
     * Making a request by giving in raw json as the query
     * @param  jsonstring  $query       Raw json query
     * @return array                    An array containing the mapped entities.
     */
    public function requestByType($query, $type = 'organisation,vacancy,person', $requestType = Request::GET)
    {
        $client = new Client(array(
            'host' => $this->es_host,
            'port' => $this->es_port
        ));

        $path = $this->getIndex() . '/' . $type . '/_search';
        $response = $client->request($path, $requestType, $query)->getData();

        return $this->esMapper->getEntities($response['hits']['hits']);
    }

    /**
     * Convenience function to allow for searching on the person document type.
     * @param  array  $query  a PHP DQL-formatted query
     * @param  string  $term  a search term
     * @param  boolean $raw   whether or not the raw result is desired
     * @return array         json array of ES entities or arrayCollection of doctrine entities (depending on whether or not $raw is true).
     */
    public function searchForPerson($query, $term, $raw = false)
    {
        return $this->searchByType('person', $query, $term, $raw);
    }

    /**
     * Convenience function to allow for searching on the vacancy document type.
     * @param  array  $query  a PHP DQL-formatted query
     * @param  string  $term  a search term
     * @param  boolean $raw   whether or not the raw result is desired
     * @return array         json array of ES entities or arrayCollection of doctrine entities (depending on whether or not $raw is true).
     */
    public function searchForVacancy($query, $term, $raw = false)
    {
        return $this->searchByType('vacancy', $query, $term, $raw);
    }

    /**
     * Convenience function to allow for searching on the organisation document type.
     * @param  array  $query  a PHP DQL-formatted query
     * @param  string  $term  a search term
     * @param  boolean $raw   whether or not the raw result is desired
     * @return array         json array of ES entities or arrayCollection of doctrine entities (depending on whether or not $raw is true).
     */
    public function searchForOrganisation($query, $term, $raw = false)
    {
        return $this->searchByType('organisation', $query, $term, $raw);
    }

    /**
     * Convenience function to allow for searching on the organisation document type.
     * @param  array  $query  a PHP DQL-formatted query
     * @param  boolean $raw   whether or not the raw result is desired
     * @return array         json array of ES entities or arrayCollection of doctrine entities (depending on whether or not $raw is true).
     */
    public function searchForOrganisationWithQuery($query, $term, $raw = false)
    {
        $params = [
                    'index' => $this->getIndex(),
                    'type' => 'organisation',
                    'body' => [
                        'query' => $query,
                    ],
                  ];

        return ($raw ? $this->searchForRaw($params) : $this->search($params));
    }



    /**
     * Convenience function to allow for searching by document type.
     * @param  array/string  $types  a list of types (strings) to search by.
     * @param  array         $query  a PHP DQL-formatted query
     * @param  string        $term  a search term
     * @param  boolean       $raw   whether or not the raw result is desired
     * @return array                json array of ES entities or arrayCollection of doctrine entities (depending on whether or not $raw is true).
     */
    public function searchByType($types, $query, $term, $raw = false)
    {
        $params = [
                    'index' => $this->getIndex(),
                    'type' => $types,
                    'body' => [
                        'query' => $this->assembleQuery($query, $term),
                    ],
                  ];
        $query['sort'] ? $params['body']['sort'] = $query['sort'] : false;

        return ($raw ? $this->searchForRaw($params) : $this->search($params));
    }

    /**
     * Helper function for the searchByType function assembling the query array
     * @param  Array    $q      array with partial arrays for the final query
     * @param  String   $term   a search term
     * @return Array            propperly formatted query array
     */
    private function assembleQuery($q, $term){
        if(!$q['must'] && !$q['must_not'] && !$q['should'] && !$q['range']){ // if user didn't use any filters then there's a simple bool query
            if(!empty($term)){
                return [ 'match' => [ '_all' => $term ] ];

            } else {
                return ['match_all' => []];
            }
        } else{ // there'll always be a query clause in the filtered clause
            $query = [
                 'filtered' => [
                    'filter' => [],
                    'query' => [],
                 ]
            ];

            if(!$term){
                $query['filtered']['query']['match_all'] = [];
            } else {
                $query['filtered']['query']['match'] = [ '_all' => $term ];
            }

            // store filter clause here for ease of working
            $filter = [];

            // check for presence of a bool clause
            if($q['must'] || $q['must_not'] || $q['should']){
                $filter['bool'] = [];
                $q['must'] ? $filter['bool']['must'] = $q['must'] : false;
                // check for presence of a range clause
                $q['range'] ? $filter['bool']['must'][] = [ 'range' => $q['range']] : false;
                $q['must_not'] ? $filter['bool']['must_not'] = $q['must_not'] : false;
                $q['should'] ? $filter['bool']['should'] = $q['should'] : false;
            }

            //if distance then filter by distance after applying any and all other filters
            if($q['distance']){
                $filter['geo_distance'] = [
                    'distance' => $q['distance'],
                    'location' => $q['location']
                ];
            }

            //add to the $query filter clause
            $query['filtered']['filter'] = $filter;
        }
            return $query;
    } //TODO: veranderen naar een multi_match query (zie p 106)
}
