<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; 
use AppBundle\Entity\Form\PersonType;
use AppBundle\Entity\Organisation;
use AppBundle\Entity\Vacancy;
use AppBundle\Entity\Form\VacancyType;

class PersonController extends controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/persoon/{username}", name="person_username")
     */
    public function personViewAction($username)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('AppBundle:Person')
            ->findOneByUsername($username);
        return $this->render('person/persoon.html.twig',
            ["person" => $person]);
    }

    /**
     * @Route("/persoon", name="self_profile")
     */
    public function selfAction()
    {
        // logged in
        if ($this->get('security.authorization_checker')
        ->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            return $this->redirectToRoute("person_username",
            ["username" => $this->getUser()->getUsername()]);
        }
        else //not logged in
        {
            return $this->redirectToRoute("login");
        }
    }


    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/mijnprofiel", name="profile_edit")
     */
    public function editProfileAction(Request $request){
        $person = $this->getUser(); 
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl("self_profile" ));
        }

        return $this->render("person/edit_profile.html.twig", array("form" => $form->createView() ));
    }


    /*
    public function listRecentPersonsAction($nr)
    {
        $entities = $this->getDoctrine()
                        ->getRepository("AppBundle:Person")
                        ->findBy(array(), array('id' => 'DESC'), $nr);
        return $this->render('person/recente_vrijwilligers.html.twig',
            ['persons' => $entities]);
    }
    */
}
