<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Organisation;
use AppBundle\Entity\Vacancy;
use AppBundle\Entity\Form\VacancyType;

class PersonController extends controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/persoon/{username}", name="person_username")
     */
    public function PersonViewAction($username)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('AppBundle:Person')
            ->findOneByUsername($username);
        return $this->render('person/persoon.html.twig',
            ["person" => $person]);
    }

    public function listRecentPersonsAction($nr)
    {
        $entities = $this->getDoctrine()
                        ->getRepository("AppBundle:Person")
                        ->findBy(array(), array('id' => 'DESC'), $nr);
        return $this->render('person/recente_vrijwilligers.html.twig',
            ['persons' => $entities]);
    }
}
