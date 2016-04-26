<?php

namespace AppBundle\Controller;

<<<<<<< HEAD
=======
<<<<<<< HEAD
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
=======
use AppBundle\Entity\Organisation;
use AppBundle\Entity\Vacancy;
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
<<<<<<< HEAD
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Organisation;
use AppBundle\Entity\Vacancy;
use AppBundle\Entity\Form\VacancyType;


=======
>>>>>>> master
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613

class PersonController extends controller
{
    /**
<<<<<<< HEAD
     *
     * @Security("has_role('ROLE_USER')")
     * @Route("/persoon/{id}" , name="person_id")
=======
<<<<<<< HEAD
     *
     * @Security("has_role('ROLE_USER')")
     * @Route("/persoon/{id}" , name="person_id")
     */
    public function ViewPersonIdAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('AppBundle:Person')
=======
     * @Route("/persoon/{id}" , name="persoon_id")
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
     */
    public function ViewPersonIdAction($id)
    {
        $em = $this->getDoctrine()->getManager();
<<<<<<< HEAD
        $person = $em->getRepository('AppBundle:Person')
=======
        $person = $em->getRepository('AppBundle:Volunteer')
>>>>>>> master
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
            ->findOneById($id);

        return $this->render('person/persoon.html.twig', array(
            "person" => $person
        ));
    }
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613

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
<<<<<<< HEAD
=======
=======
>>>>>>> master
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
}
