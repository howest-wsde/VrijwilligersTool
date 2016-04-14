<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Organisation;
use AppBundle\Entity\Vacancy;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Form\VacancyType;
use Symfony\Component\HttpFoundation\Request;

class PersonController extends controller
{
    /**
     * @Route("/persoon/{id}" , name="persoon_id")
     */
    public function ViewPersonAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('AppBundle:Volunteer')
            ->findOneById($id);

        return $this->render('person/persoon.html.twig', array(
            "person" => $person
        ));
    }
}
