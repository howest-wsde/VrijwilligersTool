<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Person;
use AppBundle\Entity\Skill;

class PlaceholderController extends Controller
{
    /**
     * @Route("/vacatures", name="vacaturesopmaat")
     */
    public function vacaturesopmaat()
    {
        return $this->render("person/vacaturesopmaat.html.twig");
    }

    /**
     * @Route("/vrijwilligerspelregels", name="person_spelregels")
     */
    public function person_spelregels()
    {
        return $this->render("person/spelregels.html.twig");
    }

    /**
     * @Route("/vereniging/{id}", name="vereniging_detail")
     */
    public function vereniging($id)
    {
        return $this->render("vereniging/vereniging.html.twig");
    }

    /**
     * @Route("/verenigingspelregels", name="vrijwilliger_vinden_spelregels")
     */
    public function verenigingspelregels()
    {
        return $this->render("organisation/spelregels.html.twig");
    }

    /**
     * @Route("/verenigingvrijwilligers", name="vrijwilliger_vinden_bekijkvrijwilligers")
     */
    public function vrijwilligervinden()
    {
        return $this->render("organisation/vrijwilligersopmaat.html.twig");
    }

    /**
     * @Route("/js/vars.js", name="javascriptvariables")
     */
    public function javascriptvariables()
    {
        return $this->render("javascript_variables.js.twig");
    }

    /**
     * @Route("/locale")
     * TODO: remove in final version
     */
    public function localeAction(Request $request)
    {
        return new Response($request->getLocale());
    }
}
