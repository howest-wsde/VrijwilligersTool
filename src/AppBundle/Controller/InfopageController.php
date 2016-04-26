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
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
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
<<<<<<< HEAD
    public function overons()    
    {
        return $this->render("info/over_ons.html.twig");
=======
    public function overons()
<<<<<<< HEAD
    {
        return $this->render("info/over_ons.html.twig");
    }

=======
    { 
        return $this->render("info/over_ons.html.twig"); 
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
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
=======
<<<<<<< HEAD
    {
        return $this->render("info/vrijwilligersinfo.html.twig");
    }

=======
    { 
        return $this->render("info/vrijwilligersinfo.html.twig"); 
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
    }


>>>>>>> master
    /**
     * @Route("/wetgeving", name="wetgeving")
     */
    public function wetgeving()
<<<<<<< HEAD
    {
        return $this->render("info/wetgeving.html.twig");
=======
<<<<<<< HEAD
    {
        return $this->render("info/wetgeving.html.twig");
    }

=======
    { 
        return $this->render("info/wetgeving.html.twig"); 
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
    }


>>>>>>> master
    /**
     * @Route("/spelregels", name="spelregels")
     */
    public function spelregels()
<<<<<<< HEAD
    {
        return $this->render("info/spelregels.html.twig");
=======
<<<<<<< HEAD
    {
        return $this->render("info/spelregels.html.twig");
    }

=======
    { 
        return $this->render("info/spelregels.html.twig"); 
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
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
<<<<<<< HEAD
    {
        return $this->render("info/faq.html.twig");
    }
=======
    { 
        return $this->render("info/faq.html.twig"); 
    } 
>>>>>>> master
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
}
