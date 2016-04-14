<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PersonController extends controller
{
    /**
     * @Route("/persoon/{id}" , name="persoon_id")
     *
     * @Security("has_role('ROLE_USER')")
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
