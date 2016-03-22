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
     * @Route("/vacature/{id}", name="vacature_detail")
     */
    public function vacature($id)
    {
        $em = $this->getDoctrine()->getManager();
        $vacancy = $em->getRepository('AppBundle:Vacancy')->find($id);
        return $this->render("vacature/vacature.html.twig",array('vacature' => $vacancy));
    }

    /**
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


    /**
     * @Route("/vereniging/{id}", name="vereniging_detail")
     */
    public function vereniging($id)
    { 
        return $this->render("vereniging/vereniging.html.twig"); 
    }

    /**
     * @Route("/vereniging", name="vrijwilliger_vinden")
     */
    public function vrijwilligerworden()
    { 
        return $this->render("vereniging/vrijwilliger_vinden.html.twig"); 
    }

    /**
     * @Route("/zoeken", name="zoekpagina")
     */
    public function zoekpagina()
    { 
        return $this->render("zoekpagina.html.twig"); 
    }


}
