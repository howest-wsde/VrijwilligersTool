<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
}
