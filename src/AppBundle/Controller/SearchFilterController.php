<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Person;
use AppBundle\Entity\Skill;

class SearchFilterController extends Controller
{
    public function listRecentSearchFiltersAction($nr)
    {
        $filters = $this->getDoctrine()
            ->getRepository("AppBundle:SearchFilter")
            ->createQueryBuilder("f")
            ->where("f.owner = :owner")
            ->orderBy("f.id", "DESC")
            ->setparameter("owner", $this->getuser())
            ->setMaxResults($nr)
            ->getQuery()
            ->getResult();
            
        return $this->render("searchResult/searchFilter.html.twig",
            ["searchFilters" => $filters]);
    }
}
