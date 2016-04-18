<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Person;
use AppBundle\SearchResult;

class SearchController extends Controller
{
    private function searchForEntityResults($search)
    {
        $query = $this->get('ElasticsearchQuery');
        $params = [
            'index' => $query->getIndex(),
            'type' => ['Person', 'vacancy', 'organisation'],
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

        return $this->render("zoekpagina.html.twig", array(
            "results" => $results,
            "query" => $query
        ));
    }

    /**
     * @Route("/zoek", name="zoek")
     */
    public function searchRedirAction()
    {
        return $this->redirectToRoute("zoeken");
    }
}
