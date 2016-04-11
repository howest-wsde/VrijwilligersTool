<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Volunteer;
use AppBundle\SearchResult;

class SearchController extends Controller
{
    private function searchPerson($name)
    {
        $query = $this->get('ElasticsearchQuery');
        $params = [
            'index' => $query->getIndex(),
            'type' => 'volunteer',
            'body' => [
                'query' => [
                    'query_string' => [
                        'query' => $name
                    ]
                ]
            ]
        ];
        $result = $query->search($params);
        return $query->getEntities();
    }

    private function searchAllEntities($search)
    {
        $query = $this->get('ElasticsearchQuery');
        $params = [
            'index' => $query->getIndex(),
            'type' => ['volunteer', 'vacancy'],
            'body' => [
                'query' => [
                    'query_string' => [
                        'query' => $search
                    ]
                ]
            ]
        ];
        $result = $query->search($params);
        return $query->getEntities();
    }

    /**
     * @Route("/search/volunteer/{name}", name="search_byname")
     */
    public function searchVolunteerByNameAction($name)
    {
        foreach ($this->searchPerson($name) as $entity) {
            echo $entity."<br />";
        }

        $message = "finished! ";
        $html = "<html><body><br />".$message."<br /></body></html>";
        return new Response($html);
    }

    /**
     * @Route("/zoeken", name="zoeken")
     */
    public function zoekQueryAction()
    {
        $query = Request::createFromGlobals()->query->get("q");
        $results_view = array();
        if ($query)
        {
            $entities = $this->searchAllEntities($query);
            foreach ($entities as $entity) {
                $result = SearchResult::fromEntity($entity);
                array_push($results_view, $result);
            }
        }

        return $this->render("zoekpagina.html.twig",
            ["results" => $results_view]
        );
    }
}
