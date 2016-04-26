<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Person;
use AppBundle\Entity\Skill;

class InfopageController extends Controller
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


    /**
     * @Route("/vrijwilligersinfo", name="vrijwilligersinfo")
     */
    public function vrijwilligersinfo()
    {
        return $this->render("info/vrijwilligersinfo.html.twig");
    }


    /**
     * @Route("/wetgeving", name="wetgeving")
     */
    public function wetgeving()
    {
        return $this->render("info/wetgeving.html.twig");
    }


    /**
     * @Route("/spelregels", name="spelregels")
     */
    public function spelregels()
    {
        return $this->render("info/spelregels.html.twig");
    }


    /**
     * @Route("/faq", name="faq")
     */
    public function faq()
    {
        return $this->render("info/faq.html.twig");
    }
    
}
