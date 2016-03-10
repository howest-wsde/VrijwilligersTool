<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Volunteer;
use AppBundle\Entity\Contact;
use AppBundle\Entity\Skillproficiency;
use AppBundle\Entity\Skill;

class VacatureController extends Controller
{ 
    /**
     * @Route("/vacature/{whateverid}")
     */
    public function layoutTest($whateverid)
    { 
        return $this->render("vacature/show.html.twig",  ["id" => $whateverid]); 
    }
}
