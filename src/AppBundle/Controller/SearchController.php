<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Person;
use AppBundle\Entity\Volunteer;
use AppBundle\Entity\SearchFilter;
use AppBundle\Entity\Form\SearchFilterType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SearchController extends Controller
{
    /**
     * [performSearch description]
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    private function performSearch($params){
        $query = $this->get("ElasticsearchQuery");
        $params = [
            'index' => $query,
            'type' => $params['type'] ? $params['type'] : 'vacancy',
        ];

        return $query->search($params);
    }

    /**
     *
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
     * [specificSearch description]
     * @param  [type] $types [description]
     * @param  [type] $body  [description]
     * @param  array  $slice [description]
     * @return [type]        [description]
     */
    private function specificSearch($types, $body, $slice = [0 => 25])
    {
        $query = $this->get("ElasticsearchQuery");
        $params = [
            "index" => $query->getIndex(),
            "type" => $types,
            "from" => key($slice),
            "size" => $slice[key($slice)],
            "body" => $body
        ];

        return $query->search($params);
    }

    /**
     * Helper function to perform a ES query by type.
     * @param  Form     $form       a search filter form
     * @param  String   $searchTerm a term to search for
     * @return Array             an array of hydrated results
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
     */
    public function searchFormPostAction(Request $request){
        //sort: afstand werkt nog niet => check geo_distance_range filter
        $sf = new SearchFilter();
        $form = $this->createForm(SearchFilterType::class, $sf);
        $form->handleRequest($request);
        $searchTerm = $form->get('search')->getData();

        return $this->render("search/zoekpagina.html.twig", array(
            // "distance" => $form->get('distance')->getData(),
            "form" => $form->createView(),
            "results" => $this->searchByType($form, $searchTerm),
            "searchTerm" => $searchTerm,
            "filters" => true,
        ));
    }

    /**
     * Zoekformulier verwerken bij een get/head request => met Q-string
     * @Route("/search", name="getSearch")
     * @Route("/zoeken", name="getZoeken")
     * @Route("/zoek", name="getZoek")
     * @Method({"GET", "HEAD"})
     */
    public function searchFormAction(Request $request){
        $searchTerm = $request->query->get("q");
        $sf = $this->createSearchFilter($request->query->all());
        $form = $this->createForm(SearchFilterType::class, $sf);
        $form->handleRequest($request);

        return $this->render("search/zoekpagina.html.twig", array(
            // "distance" => $form->get('distance')->getData(),
            "form" => $form->createView(),
            "results" => $this->searchByType($form, $searchTerm),
            "searchTerm" => $searchTerm,
            "filters" => true,
        ));
    }

    //deprecated route, stuff from Jelle (nog niet wegsmijten, moet nog stuk uit gerefactored worden)
    public function searchAction()
    {
        $request = Request::createFromGlobals();

        $form = $this->createForm(SearchFilterType::class, new SearchFilter, ["method" => "GET"]);
        $form->handleRequest($request);

        $searchTerm = $request->query->get("q");
        $results = null;
        if ($searchTerm)
        {
            $results = $this->plainSearch($searchTerm);
        }
        else if ($request->query->get("cat"))
        {
            $cat = urldecode($request->query->get("cat"));

            $em = $this->getDoctrine()->getManager();

            $childCategories = $em->getRepository("AppBundle:Skill")
                ->createQueryBuilder("s1")
                ->join("AppBundle:Skill", "s2", "WITH", "s1.parent = s2")
                ->where("s2.name = :parentName")
                ->setParameter("parentName", $cat)
                ->getQuery()
                ->getResult();

            $allvacancies = [];
            foreach ($childCategories as $category) {
                foreach ($category->getVacancies() as $vacancy) {
                    if(!in_array($vacancy, $allvacancies))
                    {
                        $allvacancies[] = $vacancy;
                    }
                }
            }

            $results = $allvacancies;
        }
        else if ($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();

            $types = array();
            if ($data->getPerson()) {array_push($types, "person");}
            if ($data->getOrganisation()) {array_push($types, "organisation");}
            if ($data->getVacancy()) {array_push($types, "vacancy");}

            $query = $data->getTerm() ? ["query_string" => ["query" => $data->getTerm()]] : ["query" => ["match_all" => []]];

            $results = $this->specificSearch($types, ["query" => $query]);
        }

        return $this->render("search/zoekpagina.html.twig", array(
            "form" => $form->createView(),
            "results" => $results,
            "searchTerm" => $searchTerm,
            "filters" => false,
        ));
    }

    /**
     * @Route("/api/search", name="api_search")
     */
    public function apiSearchAction()
    {
        $request = Request::createFromGlobals();
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
     * function to build the form for the searchFormAction Controller
     * @param  array $defaultData any data that has to be entered into the field
     * @return form
     */
    private function buildSearchForm($defaultData = array()){
        $t = $this->get('translator');

        return $this->createFormBuilder($defaultData)
        ->add("search", SearchType::class, array(
            "label" => false,
            "required" => false,
            "attr" => array("placeholder" => $t->trans('search.placeholder.searchTerm'))
        ))
        ->add("submit", SubmitType::class, array(
            "label" => $t->trans('search.label.search'),
        ))
        ->add('sort', ChoiceType::class, array(
            "label" => $t->trans('search.label.sort'),
            'choices'  => array(
                $t->trans('search.choices.distance') => 'distance',
                $t->trans('search.choices.date') => 'date',
                $t->trans('search.choices.endDate') => 'endDate',
                $t->trans('search.choices.reward') => 'reward',
            ),
            // render as select box
            'expanded' => false,
            'multiple' => false,
            'required' => false,
        ))
        ->add("person", CheckboxType::class, array(
            "label" => $t->trans('search.label.person'),
            "required" => false,
        ))
        ->add("org", CheckboxType::class, array(
            "label" => $t->trans('search.label.organisation'),
            "required" => false,
        ))
        ->add("vacancy", CheckboxType::class, array(
            "label" => $t->trans('search.label.vacancy'),
            "required" => false,
        ))
        ->add("sectors", EntityType::class, array(
            "label" => false,
            "placeholder" => false,
            // query choices from this entity
            'class' => 'AppBundle:Skill',
            //only pick skills that are childs of the sector skill
            'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('s')
                        ->where('s.parent = 36')
                        ->orderBy('s.name', 'ASC');
                },
            // use the name property as the visible option string
            'choice_label' => 'name',
            // render as select box
            'expanded' => true,
            'multiple' => true,
            'required' => false,
        ))
        ->add('categories', EntityType::class, array(
            'label' => false,
            // query choices from this entity
            'class' => 'AppBundle:Skill',
            // use the name property as the visible option string
            'choice_label' => 'name',
            // render as checkbox
            'expanded' => true,
            'multiple' => true,
            'required' => false,
            ))
        ->add('intensity', ChoiceType::class, array(
            'label' => false,
            'choices'  => array(
                $t->trans('search.choices.long') => 'long',
                $t->trans('search.choices.1time') => '1time',
            ),
            // render as checkbox
            'expanded' => true,
            'multiple' => true,
            'required' => false,
        ))
        ->add('hoursAWeek', IntegerType::class, array(
            'label' => $t->trans('search.label.hoursAWeek'),
            'required' => false,
        ))
        ->add('distance', IntegerType::class, array(
            'label' => $t->trans('search.label.distance'),
            'required' => false,
        ))
        ->add('characteristic', ChoiceType::class, array(
            'label' => false,
            'choices'  => array(
                $t->trans('search.choices.weelchair') => 'weelchair',
                $t->trans('search.choices.lotsContact') => 'lotsContact',
                $t->trans('search.choices.littleContact') => 'littleContact',
            ),
            // render as checkbox
            'expanded' => true,
            'multiple' => true,
            'required' => false,
        ))
        ->add('advantages', ChoiceType::class, array(
            'label' => false,
            'choices'  => array(
                $t->trans('search.choices.renumeration') => 'reward',
                $t->trans('search.choices.other') => 'other',
            ),
            // render as checkbox
            'expanded' => true,
            'multiple' => true,
            'required' => false,
        ))
        ->getForm();
    }

    /**
     * helper function for searchAction to generate an array of results based on
     * the different use cases
     * @param  string   $searchTerm   a search term
     * @param  form     $form         the submitted form
     * @param  request  $request      the submitted request
     * @return array of results
     */
    private function getResultsOfSearch($searchTerm, $form = null, $request = null){
        if ($searchTerm && is_null($form))
        {
            return $this->plainSearch($searchTerm);
        }
        else if ($request->query->get("cat"))
        {
            return $this->catSearch($request);
        }
        else if ($form->isSubmitted() && $form->isValid())
        {
            return $this->validFormSearch($form);
        }
    }

    /**
     * Helper function for getResultsOfSearch() to be executed when the query-string
     * contains "cat"
     * @param  $request
     * @return array of results
     */
    private function catSearch($request){
        $cat = urldecode($request->query->get("cat"));

        $em = $this->getDoctrine()->getManager();

        $childCategories = $em->getRepository("AppBundle:Skill")
            ->createQueryBuilder("s1")
            ->join("AppBundle:Skill", "s2", "WITH", "s1.parent = s2")
            ->where("s2.name = :parentName")
            ->setParameter("parentName", $cat)
            ->getQuery()
            ->getResult();

        $allvacancies = [];
        foreach ($childCategories as $category) {
            foreach ($category->getVacancies() as $vacancy) {
                if(!in_array($vacancy, $allvacancies))
                {
                    $allvacancies[] = $vacancy;
                }
            }
        }

        return $allvacancies;
    }

    /**
     * Helper function for getResultsOfSearch() to be executed when a valid form entry has been submitted
     * @param  $request
     * @return array of results
     */
    private function validFormSearch($form){
        //TODO: seriously flesh this out!
        $data = $form->getData();
        $searchTerm = $data['search'];

        return $this->plainSearch($searchTerm);

        // $types = array();
        // if ($data->getPerson()) {array_push($types, "person");}
        // if ($data->getOrganisation()) {array_push($types, "organisation");}
        // if ($data->getVacancy()) {array_push($types, "vacancy");}

        // $query = $data->getTerm() ? ["query_string" => ["query" => $data->getTerm()]] : ["query" => ["match_all" => []]];

        // return $this->specificSearch($types, ["query" => $query]);
    }

    /**
     * Assemble the array of document types to search
     * @param  Form     $form   the search form as posted by the user
     * @return Array            an array of ES document types
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
     * Use a submitted buildSearchForm to assemble a valid ES query
     * @param  Form     $form   the search form as posted by the user
     * @return Array            a valid ES query in php format
     */
    private function assembleQuery($form){
        $categories = $form->get('categories')->getData(); //array
        $sectors = $form->get('sectors')->getData(); //array
        $intensity = $form->get('intensity')->getData(); //array
        $hoursAWeek = $form->get('estimatedWorkInHours')->getData(); //int
        $characteristic = $form->get('characteristic')->getData(); //array
        $advantages = $form->get('advantages')->getData(); //array
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
        ];
    }

    /**
     * Helper function for assembleQuery, processing the sort preference of the user
     * @param  String   $sort     the sort preference of the user
     * @return Array              array representing a sort clause
     */
    private function processSort($sort){
        return [ $sort => [ 'order' => ($sort === 'reward' ? 'desc' : 'asc' ) ]];
    }

    /**
     * Helper function for assembleQuery, processing the advantages array
     * @param  String   $advantages   the advantages the user wants to filter on
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
     * @param  String   $characteristic   the characteristic the user wants to filter on
     * @param  String   $must             the array representing the must filter
     * @return Array                      array representing a term/s filter
     */
    private function processCharacteristic($characteristic, &$must){
        foreach ($characteristic as $key => $value) {
            switch ($value) {
                case 'weelchair':
                    $must[] = [ 'term' => [ 'accessible' => true ]];
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
     * @return Array                   array representing a term/s filter
     */
    private function processIntensity(&$intensity){
        return ['term' => [ 'longterm' => ($intensity === '1time' ? false : true) ]];
    }

    /**
     * Helper function for assembleQuery, processing the skill arrays
     * @param  Array    $array      all skills the user wants to filter on
     * @param  string   $termName   the name of the term in ES to filter on
     * @return Array                array representing a term/s filter
     */
    private function processSkillArray(&$array, $termName){
        if(sizeof($array) > 1){
            $skills = [];
            foreach ($array as $key => $skill) {
                $skills[] = $skill->getName();
            }
        } else {
            $skils = $array[0]->getName();
        }
        return [(sizeof($array) > 1 ? 'terms' : 'term') => [ $termName => $skills ]];
    }

    /**
     * Create an instance of SearchFilter to be used in the search page form.
     * @param  Array        $array      either a post or get array of values
     * @return AppBundle\Entity\SearchFilter        a Search Filter
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
                            foreach ($value as $key => $id) {
                                # code...
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
