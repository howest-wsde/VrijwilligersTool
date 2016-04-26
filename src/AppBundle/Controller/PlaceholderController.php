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
use AppBundle\Entity\Volunteer;
use AppBundle\Entity\Contact;
use AppBundle\Entity\Skillproficiency;
>>>>>>> master
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
use AppBundle\Entity\Skill;

class PlaceholderController extends Controller
{
    /**
<<<<<<< HEAD
     * @Route("/vacatures", name="vacaturesopmaat")
=======
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
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
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
<<<<<<< HEAD
        return $this->render("person/spelregels.html.twig");
    } 
=======
        return $this->render("vrijwilliger/worden.html.twig");
    }

>>>>>>> master
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613

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
<<<<<<< HEAD
     * @Route("/plaatsvacature", name="vrijwilliger_vinden_plaatsvacature")
=======
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
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
     */
    public function maakvacature()
    {
        return $this->render("organisation/plaatsvacature.html.twig");
    }
<<<<<<< HEAD

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
>>>>>>> master
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
}
