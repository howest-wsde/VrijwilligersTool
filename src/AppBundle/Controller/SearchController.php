<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Person;
use AppBundle\Entity\Volunteer;
use AppBundle\Entity\Form\SearchFilter;
use AppBundle\Entity\Form\SearchFilterType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SearchController extends Controller
{
    private function performSearch($params){
        $query = $this->get("ElasticsearchQuery");
        $params = [
            'index' => $query,
            'type' => $params['type'] ? $params['type'] : 'vacancy',
        ];

        $results = $query->search($params);
        return $query->getResults();
    }

    private function plainSearch($term, $types = ["person", "vacancy", "organisation"])
    {
        $query = $this->get("ElasticsearchQuery");
        $params = [
            "index" => $query->getIndex(),
            "type" => $types,
            "body" => [
                "query" => [
                    "query_string" => [
                        "query" => $term
                    ]
                ]
            ]
        ];
        $result = $query->search($params);
        return $query->getResults();
    }

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
        $result = $query->search($params);
        return $query->getResults();
    }

    /**
     * @Route("/search", name="search")
     */
    public function searchFormAction(){
        $request = Request::createFromGlobals();
        $results = [];
        $searchTerm = $request->query->get("q");
        $defaultData = array('search' => $searchTerm, );

        $form = $this->createFormBuilder($defaultData)
        ->add("search", SearchType::class, array(
            "label" => false,
            "required" => false,
            "attr" => array("placeholder" => "(optioneel) zoekterm")
        ))
        ->add("submit", SubmitType::class, array(
            "label" => "Zoeken",
        ))
        ->add("person", CheckboxType::class, array(
            "label" => "personen",
            "required" => false,
        ))
        ->add("organisation", CheckboxType::class, array(
            "label" => "verenigingen",
            "required" => false,
        ))
        ->add("vacancy", CheckboxType::class, array(
            "label" => "vacatures",
            "required" => false,
        ))
        ->add('categories', EntityType::class, array(
            'label' => false,
            // query choices from this entity
            'class' => 'AppBundle:Skill',
            // use the name property as the visible option string
            'choice_label' => 'name',
            // render as select box
            'expanded' => true,
            'multiple' => true,
            'required' => false,
        ))
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

        }


        return $this->render("search/zoekpagina.html.twig", array(
            "form" => $form->createView(),
            "results" => $results,
            "searchTerm" => $searchTerm,
            "filters" => true,
        ));
    }

    /**
     * @Route("/zoeken", name="zoeken")
     * @Route("/zoek", name="zoek")
     */
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
            "searchTerm" => $searchTerm
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
}
