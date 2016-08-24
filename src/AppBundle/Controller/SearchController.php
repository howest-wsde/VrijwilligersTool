<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\SearchFilter;
use AppBundle\Entity\Form\SearchFilterType;

class SearchController extends Controller
{
    /**
     * @param [type]    $term   description
     * @param [type]    $ypes   description
     */
    private function plainSearch($term, $types = ["person", "vacancy", "organisation"])
    {
        $query = $this->get("ElasticsearchQuery");
        $params = [
            'index' => $query->getIndex(),
            'type' => $types,
            'body' => [
                'query' => [
                    'query_string' => [
                        'query' => $term,
                    ],
                ],
            ],
        ];

        return $query->search($params);
    }

    /**
     * Helper function to perform a ES query by type.
     * @param  \Symfony\Component\Form\Form     $form       a search filter form
     * @param  String   $searchTerm a term to search for
     * @return array             an array of hydrated results
     */
    private function searchByType($form, $searchTerm){
        $ESquery = $this->get("ElasticsearchQuery");
        $types = $this->getTypes($form);
        $query = $this->assembleQuery($form);

        return $ESquery->searchByType($types, $query, $searchTerm);
    }

    /**
     * Zoekformulier verwerken bij een post request => niks geen Q-string
     * @Route("/search", name="search")
     * @Route("/zoeken", name="zoeken")
     * @Route("/zoek", name="zoek")
     * @Method("POST")
     * @param Request $request
     * @return Response
     */
    public function searchFormPostAction(Request $request){
        //sort: afstand werkt nog niet
        $sf = new SearchFilter();
        $form = $this->createForm(SearchFilterType::class, $sf);
        $form->handleRequest($request);
        $searchTerm = $form->get('search')->getData();

        return $this->render("search/zoekpagina.html.twig", array(
            "form" => $form->createView(),
            "results" => $this->searchByType($form, $searchTerm),
            "searchTerm" => $searchTerm,
            "filters" => true,
        ));
        // return $this->redirectToRoute('postsearch', array(
        //                         'form' => $form,
        //                         'searchTerm' => $searchTerm
        //                     )
        //         );
    }

    // /**
    //  * Helper function for searchFormPostAction to clear out the query string should one exist from a previous get request.
    //  * @Route("/postSearch", name="postsearch")
    //  */
    // public function postSearchAction($form, $searchTerm){
    //     return $this->render("search/zoekpagina.html.twig", array(
    //         "form" => $form->createView(),
    //         "results" => $this->searchByType($form, $searchTerm),
    //         "searchTerm" => $searchTerm,
    //         "filters" => true,
    //     ));
    // }

    /**
     * Zoekformulier verwerken bij een get/head request => met Q-string
     * @Route("/search", name="getSearch")
     * @Route("/zoeken", name="getZoeken")
     * @Route("/zoek", name="getZoek")
     * @Method({"GET", "HEAD"})
     * @param Request $request
     * @return Response
     */
    public function searchFormAction(Request $request){
        $searchTerm = $request->query->get("q");
        $sf = $this->createSearchFilter($request->query->all());
        $form = $this->createForm(SearchFilterType::class, $sf);
        $form->handleRequest($request);

        return $this->render("search/zoekpagina.html.twig", array(
            "form" => $form->createView(),
            "results" => $this->searchByType($form, $searchTerm),
            "searchTerm" => $searchTerm,
            "filters" => true,
        ));
    }

    /**
     * Wordt gebruikt in base.html.twig (lijn 16, RV_GLOBALS)
     * @Route("/api/search", name="api_search")
     */
    public function apiSearchAction()
    {
        $request = Request::createFromGlobals();
        $results = null;
        $query = $request->query->get("q");
        if ($query)
        {
            $results = $this->plainSearch($query);
        }

        $response = new Response(
            $this->renderView("search/zoekapi_resultaat.html.twig",
                ["results" => $results]),
                200
            );

        $response->headers->set("Access-Control-Allow-Origin", "*");
        return $response;
    }


    /**
     * Wordt gebruikt in base.html.twig (lijn 17, RV_GLOBALS)
     * @Route("/api/usersearch", name="api_usersearch")
     */
    public function apiSearchUserAction()
    {
        $request = Request::createFromGlobals();
        $query = $request->request->get("person");
        //var_dump($request);
        if ($query) {
            $results = $this->plainSearch($query, ["person"]);
        } else $results = [];
        $response = new Response(
            $this->renderView("person/usersearch.user.html.twig",
                ["results" => $results]),
                200
            );
        return $response;
    }

    /**
     * Assemble the array of document types to search
     * @param  \Symfony\Component\Form\Form     $form   the search form as posted by the user
     * @return array            an array of ES document types
     */
    private function getTypes($form){
        $types = [];

        //get types to search for
        $person = $form->get('person')->getData(); //bool
        $org = $form->get('org')->getData(); //bool
        $vacancy = $form->get('vacancy')->getData(); //bool

        if(($person && $org && $vacancy) || (!$person && !$org && !$vacancy)){ //search for all as user either selected all or none
            $types = ["person", "vacancy", "organisation"];
        } else {
            $person ? $types[] = 'person' : false;
            $org ? $types[] = 'organisation' : false;
            $vacancy ? $types[] = 'vacancy' : false;
        }

        return $types;
    }

    /**
     * Use a submitted SearchForm to assemble a valid ES query
     * @param  \Symfony\Component\Form\Form     $form   the search form as posted by the user
     * @return array            a valid ES query in php format
     */
    private function assembleQuery($form){
        $categories = $form->get('categories')->getData(); //array
        $sectors = $form->get('sectors')->getData(); //array
        $intensity = $form->get('intensity')->getData(); //array
        $hoursAWeek = $form->get('estimatedWorkInHours')->getData(); //int;
        $characteristic = $form->get('characteristic')->getData(); //array
        $advantages = $form->get('advantages')->getData(); //array
        $distance = $form->get('distance')->getData(); //int
        $sort = $form->get('sort')->getData(); //string
        $should = [];
        $must = [];
        $must_not = [];
        $range = [];
        $exists = [];

        if(!empty($categories) && !$categories->isEmpty()){
          $should[] = $this->processSkillArray($categories->toArray(), 'skills.name');
        }

        if(!empty($sectors) && !$sectors->isEmpty()){
          $should[] = $this->processSkillArray($sectors->toArray(), 'sectors.name');
        }

        if(!empty($intensity && sizeof($intensity) === 1)){
          $should[] = $this->processIntensity($intensity[0]);
        }

        if($hoursAWeek){
          $range['estimatedWorkInHours'] = [ 'lte' => $hoursAWeek ];
        }


        if(!empty($characteristic)){
            $this->processCharacteristic($characteristic, $must);
        }

        if(!empty($advantages)){
            $this->processAdvantages($advantages, $exists, $range);
        }

        if($distance){
            $dist = $this->processDistance($distance);
            $loc = $this->getUserLocation();
        }

        if($sort){
            $sort = $this->processSort($sort);
        }

        return [
            'must' => (!empty($must) ? $must : false),
            'must_not' => (!empty($must_not) ? $must_not : false),
            'should' => (!empty($should) ? $should : false),
            'range' => (!empty($range) ? $range : false),
            'sort' => (!empty($sort) ? $sort : false),
            'exists' => (!empty($exists) ? $exists : false),
            'distance' => (isset($dist) ? $dist : false),
            'location' => (isset($loc) ? $loc : false),
        ];
    }

    /**
     * Helper function for assembleQuery, processing the sort preference of the user
     * @param  String   $sort     the sort preference of the user
     * @return array              array representing a sort clause
     */
    private function processSort($sort){
        $score = [ '_score' => [ 'order' => 'desc' ]];
        if($sort !== 'distance'){
            $sort = [ $sort => [ 'order' => ($sort === 'reward' ? 'desc' : 'asc' ) ]];
        } else {
            $sort = [
                'geo_distance' => [
                    'location' => $this->getUserLocation(),
                    'order' => 'asc',
                    'unit' => 'km',
                    'distance_type' => 'plane'
                ]
            ];
        }

        return [
            $sort,
            $score
        ];
    }

    /**
     * Helper function for assembleQuery, processing the distance filter set by the user
     * @param $distance int
     * @return string /boolean    distance string if possible, else false
     */
    private function processDistance($distance){
        $user = $this->getUser();

        if($user->esGetLocation()){
            return ($distance . 'km');
        }

        return false;
    }

    /**
     * Helper function for assembleQuery, returning a formatted location for the user if possible
     * @return array/boolean  array holding a value for location or false if not possible
     */
    private function getUserLocation(){
        $user = $this->getUser();

        if($user->esGetLocation()){
            return [
                      'lat' => $user->getLatitude(),
                      'long' => $user->getLongitude()
                   ];
        }

        return false;
    }

    /**
     * Helper function for assembleQuery, processing the advantages array
     * @param  array   $advantages   the advantages the user wants to filter on
     * @param  String   $exists       the array representing the exists filter
     * @param  String   $range        the array representing the range filter
     */
    private function processAdvantages(&$advantages, &$exists, &$range){
        foreach ($advantages as $key => $advantage) {
            switch ($advantage) {
                case 'reward':
                    $range['renumeration'] = [ 'gt' => 0 ];
                    $exists[] = [ 'field' => 'renumeration' ];
                    break;

                case 'other':
                    $exists[] = [ 'field' => 'tags' ];
                    break;
            }
        }
    }

    /**
     * Helper function for assembleQuery, processing the characteristic array
     * @param  array   $characteristic   the characteristic the user wants to filter on
     * @param  String   $must             the array representing the must filter
     * @return array                      array representing a term/s filter
     */
    private function processCharacteristic($characteristic, &$must){
        foreach ($characteristic as $key => $value) {
            switch ($value) {
                case 'weelchair':
                    $must[] = [ 'term' => [ 'access' => true ]];
                    break;

                case 'lotsContact':
                    $must[] = [ 'term' => [ 'socialInteraction' => 'all' ]];
                    break;

                case 'littleContact':
                    $must[] = [ 'term' => [ 'socialInteraction' => 'little' ]];
                    break;
            }
        }
    }

    /**
     * Helper function for assembleQuery, processing the intensity array
     * @param  String   $intensity     the intensity the user wants to filter on
     * @return array                   array representing a term/s filter
     */
    private function processIntensity(&$intensity){
        return ['term' => [ 'longterm' => ($intensity === '1time' ? false : true) ]];
    }

    /**
     * Helper function for assembleQuery, processing the skill arrays
     * @param  array    $array      all skills the user wants to filter on
     * @param  string   $termName   the name of the term in ES to filter on
     * @return array                array representing a term/s filter
     */
    private function processSkillArray($array, $termName){
        if(sizeof($array) > 1){
            $skills = [];
            foreach ($array as $key => $skill) {
                $skills[] = $skill->getName();
            }
        } else {
            $skills = $array[0]->getName();
        }
        return [(sizeof($array) > 1 ? 'terms' : 'term') => [ $termName => $skills ]];
    }

    /**
     * Create an instance of SearchFilter to be used in the search page form.
     * @param  array        $array      either a post or get array of values
     * @return \AppBundle\Entity\SearchFilter        a Search Filter
     */
    private function createSearchFilter($array){
        $sf = new SearchFilter();
        foreach ($array as $key => $value) {
            if(!empty($value)){
                switch ($key) { //made into a switch so it can be expanded easily if that need arises in the future
                    case 'q':
                        $sf->{ 'setSearch'}($value);
                        break;
                    case 'categories':
                        if(is_array($value)){
                            foreach ($value as $thekey => $id) {
                                //TODO
                            }
                        }
                        $sf->{ 'addCategory' }($value);
                        break;
                    case 'sectors':
                        $sf->{ 'addSector' }($value);
                        break;
                    case '_token':
                    case 'submit': //watch out: fallthrough
                        false;
                        break;
                    case 'hoursAWeek':
                    case 'distance': //watch out: fallthrough
                        $sf->{ 'set' . ucfirst($key) }((int) $value);
                        break;
                    default:
                        $sf->{ 'set' . ucfirst($key) }($value);
                        break;
                }
            }
        }

        return $sf;
    }
}
