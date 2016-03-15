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

class PlaceholderController extends Controller
{ 
    /**
     * @Route("/tos", name="privacy_en_legal")
     */
    public function tos()
    { 
        return $this->render("info/privacy_en_legal.html.twig"); 
    }


    /**
     * @Route("/over_ons", name="over_ons")
     */
    public function overons()
    { 
        return $this->render("info/over_ons.html.twig"); 
    }


    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    { 
        return $this->render("info/contact.html.twig"); 
    }
}
