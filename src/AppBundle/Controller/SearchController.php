<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Person;
use AppBundle\Entity\Volunteer;
use AppBundle\Entity\Form\SearchFilterType;

class SearchController extends Controller
{
    private function plainSearch($term)
    {
        $query = $this->get("ElasticsearchQuery");
        $params = [
            "index" => $query->getIndex(),
            "type" => ["person", "vacancy", "organisation"],
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
        $sliceKey = key($slice);
        $query = $this->get("ElasticsearchQuery");
        $params = [
            "index" => $query->getIndex(),
            "type" => $types,
            "from" => $sliceKey,
            "size" => $slice[$sliceKey],
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

        $form = $this->createForm(SearchFilterType::class, null, ["method" => "GET"]);
        $form->handleRequest($request);

        $searchTerm = $request->query->get("q");
        $results = null;
        if ($searchTerm)
        {
            $results = $this->plainSearch($searchTerm);
        }
        if ($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();

            $types = array();
            if ($data["person"]) {array_push($types, "person");}
            if ($data["organisation"]) {array_push($types, "organisation");}
            if ($data["vacancy"]) {array_push($types, "vacancy");}

            $query = $data["term"] ? ["query_string" => ["query" => $data["term"]]] : ["query" => ["match_all" => []]];
            $body = ["query" => $query];

            $results = $this->specificSearch($types, $body);
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
