<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Person;
use AppBundle\Entity\Volunteer;

class SearchController extends Controller
{
    private function searchForEntityResults($search)
    {
        $query = $this->get('ElasticsearchQuery');
        $params = [
            'index' => $query->getIndex(),
            'type' => ['person', 'vacancy', 'organisation'],
            'body' => [
                'query' => [
                    'query_string' => [
                        'query' => $search
                    ]
                ]
            ]
        ];
        $result = $query->search($params);
        return $query->getResults();
    }

    /**
     * @Route("/zoeken", name="zoeken")
     * @Route("/zoek")
     */
    public function searchAction()
    {
        $query = Request::createFromGlobals()->query->get("q");
        $results = null;
        if ($query)
        {
            $results = $this->searchForEntityResults($query);
        }
        return $this->render("search/zoekpagina.html.twig", array(
            "results" => $results,
            "query" => $query
        ));
    }

    /**
     * @Route("/api/search", name="api_search")
     */
    public function apiSearchAction()
    {
        $query = Request::createFromGlobals()->query->get("q");
        $results = null;
        if ($query)
        {
            $results = $this->searchForEntityResults($query);
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
