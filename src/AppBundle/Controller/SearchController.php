<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

<<<<<<< HEAD
use AppBundle\Entity\Person;
=======
<<<<<<< HEAD
use AppBundle\Entity\Person;
=======
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
use AppBundle\Entity\Volunteer;
>>>>>>> master
use AppBundle\SearchResult;

class SearchController extends Controller
{
    private function searchForEntityResults($search)
    {
        $query = $this->get('ElasticsearchQuery');
        $params = [
            'index' => $query->getIndex(),
<<<<<<< HEAD
            'type' => ['person', 'vacancy', 'organisation'],
=======
<<<<<<< HEAD
            'type' => ['person', 'vacancy', 'organisation'],
=======
            'type' => ['volunteer', 'vacancy', 'organisation'],
>>>>>>> master
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
            'body' => [
                'query' => [
                    'query_string' => [
                        'query' => $search
                    ]
                ]
            ]
        ];
        $result = $query->search($params);
        return $query->getSearchResults();
    }

    /**
     * @Route("/zoeken", name="zoeken")
     */
    public function searchAction()
    {
        $query = Request::createFromGlobals()->query->get("q");
        $results = null;
        if ($query)
        {
            $results = $this->searchForEntityResults($query);
        }

<<<<<<< HEAD
        return $this->render("search/zoekpagina.html.twig", array(
=======
<<<<<<< HEAD
        return $this->render("search/zoekpagina.html.twig", array(
=======
        return $this->render("zoekpagina.html.twig", array(
>>>>>>> master
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
            "results" => $results,
            "query" => $query
        ));
    }

    /**
     * @Route("/zoek", name="zoek")
     */
<<<<<<< HEAD
    public function searchRedirectAction()
    {
        return $this->redirectToRoute("zoeken");
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
                array("results" => $results)),
                200
            );
        $response->headers->set("Access-Control-Allow-Origin", "*");
        return $response;
    }
=======
<<<<<<< HEAD
    public function searchRedirectAction()
    {
        return $this->redirectToRoute("zoeken");
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
                array("results" => $results)),
                200
            );
        $response->headers->set("Access-Control-Allow-Origin", "*");
        return $response;
    }
=======
    public function searchRedirAction()
    {
        return $this->redirectToRoute("zoeken");
    }
>>>>>>> master
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
}
