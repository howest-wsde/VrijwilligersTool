<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
<<<<<<< HEAD
use AppBundle\Entity\Person;
use AppBundle\Entity\Skill;

class InfopageController extends Controller
{
=======
use AppBundle\Entity\Volunteer;
use AppBundle\Entity\Contact;
use AppBundle\Entity\Skillproficiency;
use AppBundle\Entity\Skill;

class InfopageController extends Controller
{ 
>>>>>>> master
    /**
     * @Route("/tos", name="privacy_en_legal")
     */
    public function tos()
<<<<<<< HEAD
    {
        return $this->render("info/privacy_en_legal.html.twig");
    }

=======
    { 
        return $this->render("info/privacy_en_legal.html.twig"); 
    }


>>>>>>> master
    /**
     * @Route("/over_ons", name="over_ons")
     */
    public function overons()
<<<<<<< HEAD
    {
        return $this->render("info/over_ons.html.twig");
    }

=======
    { 
        return $this->render("info/over_ons.html.twig"); 
    }


>>>>>>> master
    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
<<<<<<< HEAD
    {
        return $this->render("info/contact.html.twig");
    }

=======
    { 
        return $this->render("info/contact.html.twig"); 
    }


>>>>>>> master
    /**
     * @Route("/vrijwilligersinfo", name="vrijwilligersinfo")
     */
    public function vrijwilligersinfo()
<<<<<<< HEAD
    {
        return $this->render("info/vrijwilligersinfo.html.twig");
    }

=======
    { 
        return $this->render("info/vrijwilligersinfo.html.twig"); 
    }


>>>>>>> master
    /**
     * @Route("/wetgeving", name="wetgeving")
     */
    public function wetgeving()
<<<<<<< HEAD
    {
        return $this->render("info/wetgeving.html.twig");
    }

=======
    { 
        return $this->render("info/wetgeving.html.twig"); 
    }


>>>>>>> master
    /**
     * @Route("/spelregels", name="spelregels")
     */
    public function spelregels()
<<<<<<< HEAD
    {
        return $this->render("info/spelregels.html.twig");
    }

=======
    { 
        return $this->render("info/spelregels.html.twig"); 
    }


>>>>>>> master
    /**
     * @Route("/faq", name="faq")
     */
    public function faq()
<<<<<<< HEAD
    {
        return $this->render("info/faq.html.twig");
    }
=======
    { 
        return $this->render("info/faq.html.twig"); 
    } 
>>>>>>> master
}
