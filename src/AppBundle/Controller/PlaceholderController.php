<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Person;
use AppBundle\Entity\Contact;
use AppBundle\Entity\Skillproficiency;
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
     * @Route("/verenigingaanmaken", name="vereniging_aanmaken")
     */
    public function maakvereniging()
    {
        return $this->render("organisation/maakvereniging.html.twig");
    }

    /**
     * @Route("/verenigingspelregels", name="vrijwilliger_vinden_spelregels")
     */
    public function verenigingspelregels()
    {
        return $this->render("organisation/spelregels.html.twig");
    }

    /**
     * @Route("/plaatsvacature", name="vrijwilliger_vinden_plaatsvacature")
     */
    public function maakvacature()
    {
        return $this->render("organisation/plaatsvacature.html.twig");
    }

    /**
     * @Route("/verenigingvrijwilligers", name="vrijwilliger_vinden_bekijkvrijwilligers")
     */
    public function vrijwilligervinden()
    {
        return $this->render("organisation/vrijwilligersopmaat.html.twig");
    }
}
