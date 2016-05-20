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

class SearchController extends Controller
{
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
}
