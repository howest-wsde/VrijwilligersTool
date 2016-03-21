<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Vacancy;
use AppBundle\Entity\Vacancycategory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Tests\Fixtures\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Volunteer;
use AppBundle\Entity\Contact;
use AppBundle\Entity\Skillproficiency;
use Symfony\Component\Validator\Constraints\DateTime;

class PlaceholderController extends Controller
{  
    /**
     * @Route("/vacature/{id}", name="vacature_detail")
     */
    public function vacature($id)
    {
        if(is_numeric($id) && $id > 0) {
            $em = $this->getDoctrine()->getManager();
            $vacancy = $em->getRepository('AppBundle:Vacancy')->find($id);

            if (!$vacancy)
                throw new \Exception("De gevraagde vacature werd niet gevonden :'(");
            else
                return $this->render('vacature/vacature.html.twig', array(
                    'vacature' => $vacancy,
                ));
        }
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
       // $register = new User();
        //$form = $this->createForm(UserType::class,$register);
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
