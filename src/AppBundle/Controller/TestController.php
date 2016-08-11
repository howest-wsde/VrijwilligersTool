<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class TestController extends Controller
{
    /**
     * @Route("/tests", name="tests")
     */
    public function testsAction()
    {
        return $this->render("tests/tests.html.twig");
    }

    /**
     * @Route("/test-type-vrijwilliger", name="test_type")
     */
    public function testTypeOfVolunteerAction()
    {
        return $this->render("tests/type-vrijwilliger.html.twig");
    }

    /**
     * @Route("/test-taalvaardigheden", name="test_taal")
     */
    public function testLanguageSkillsAction()
    {
        return $this->render("tests/taalvaardigheden.html.twig");
    }

    /**
     * @Route("/test-computervaardigheden", name="test_comp")
     */
    public function testCompSkillsAction()
    {
        return $this->render("tests/computervaardigheden.html.twig");
    }

    /**
     * @Route("/esperson", name="esperson")
     */
    public function testESPerson()
    {
        $request = Request::createFromGlobals();
        $searchTerm = $request->query->get("q");
        $query = $this->get("ElasticsearchQuery");
        $result = $query->searchForPerson($searchTerm);

        var_dump($result);
        exit();
    }

    /**
     * @Route("/espersonraw", name="espersonraw")
     */
    public function testESPersonRaw()
    {
        $request = Request::createFromGlobals();
        $searchTerm = $request->query->get("q");
        $query = $this->get("ElasticsearchQuery");
        $result = $query->searchForPerson($searchTerm);

        return new JSonResponse($query->searchForPersonRaw($searchTerm));
    }

    /**
     * @Route("/esfiltered", name="esfiltered")
     */
    public function testESFiltered()
    {
        $request = Request::createFromGlobals();
        $searchTerm = $request->query->get("q");
        $ESquery = $this->get("ElasticsearchQuery");


        $result = $ESquery->searchByType($types, $query, $term);

        var_dump();
        exit();
    }
}
