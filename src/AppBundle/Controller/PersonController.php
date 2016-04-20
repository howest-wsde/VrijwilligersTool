<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Organisation;
use AppBundle\Entity\Vacancy;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Form\VacancyType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PersonController extends controller
{
    /**
     * @Route("/persoon/{id}" , name="person_id")
     */
    public function ViewPersonIdAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('AppBundle:Person')
            ->findOneById($id);

        return $this->render('person/persoon.html.twig', array(
            "person" => $person
        ));
    }

    /**
     * @Route("/persoon/u/{username}" , name="person_username")
     */
    public function ViewPersonUsernameAction($username)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('AppBundle:Person')
            ->findOneByUsername($username);

        return $this->render('person/persoon.html.twig', array(
            "person" => $person
        ));
    }


    public function listRecentPersonsAction(){
        // retreiving 5 most recent persons
        $entities = $this->getDoctrine()
                        ->getRepository("AppBundle:Person")
                        ->findBy(array(), array('id' => 'DESC'),5);

        return $this->render('person/recente_vrijwilligers.html.twig',
            array('persons' => $entities)
        );

    }
}
