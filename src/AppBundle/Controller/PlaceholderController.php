<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
<<<<<<< HEAD
use AppBundle\Entity\Person;
=======
use AppBundle\Entity\Volunteer;
use AppBundle\Entity\Contact;
use AppBundle\Entity\Skillproficiency;
>>>>>>> master
use AppBundle\Entity\Skill;

class PlaceholderController extends Controller
{
    /**
<<<<<<< HEAD
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
=======
     * @Route("/vrijwilliger/{id}", name="vrijwilliger_detail")
     */
    public function vrijwilliger($id)
    {
        return $this->render("vrijwilliger/vrijwilliger.html.twig");
    }

    /**
     * @Route("/vrijwilliger", name="vrijwilliger_worden")
     */
    public function vrijwilliger_worden()
    {
        return $this->render("vrijwilliger/worden.html.twig");
    }

>>>>>>> master

    /**
     * @Route("/vereniging/{id}", name="vereniging_detail")
     */
    public function vereniging($id)
    {
        return $this->render("vereniging/vereniging.html.twig");
    }

    /**
<<<<<<< HEAD
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


    /**
     * @Route("/js/vars.js", name="javascriptvariables")
     */
    public function javascriptvariables()
    {
        return $this->render("javascript_variables.js.twig");
    }

=======
     * @Route("/vereniging", name="vrijwilliger_vinden")
     */
    public function vrijwilligerworden()
    {
        return $this->render("vereniging/vrijwilliger_vinden.html.twig");
    }
>>>>>>> master
}
