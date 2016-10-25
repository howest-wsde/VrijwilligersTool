<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Person;

class SuperadminController extends Controller
{
    /**
     * @Route("/admin", name="superadmin_main", defaults={"personcount" = 20, "organisationcount" = 20, "vacancycount" = 20} )
     * @Route("/admin/users", name="superadmin_users", defaults={"personcount" = 1000})
     * @Route("/admin/organisations", name="superadmin_organisations", defaults={"organisationcount" = 1000})
     * @Route("/admin/vacancies", name="superadmin_vacancies", defaults={"vacancycount" = 1000})
     */
    public function adminAction($personcount=0, $organisationcount=0, $vacancycount=0)
    {
        $user = $this->getUser();
        if(!$user || !$user->getSuperadmin()) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $persons = $this->getDoctrine()
            ->getRepository("AppBundle:Person")
            ->findBy(array(), array('id' => 'DESC'), $personcount);

        $organisations = $this->getDoctrine()
            ->getRepository("AppBundle:Organisation")
            ->findBy(array(), array('id' => 'DESC'), $organisationcount);

        $vacancies = $this->getDoctrine()
            ->getRepository("AppBundle:Vacancy")
            ->findBy(array(), array('id' => 'DESC'), $vacancycount);

        return $this->render('superadmin/index.html.twig', [
            'persons' => $persons,
            'organisations' => $organisations,
            'vacancies' => $vacancies
        ]);
    }


    /**
     * @Route("/admin/user/{username}", name="superadmin_user")
     */
    public function adminUserAction($username)
    {
        $user = $this->getUser();
        if(!$user || !$user->getSuperadmin()) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('AppBundle:Person')
            ->findOneByUsername($username);

        return $this->render('superadmin/user.html.twig', [
            'person' => $person
        ]);
    }

    /**
     * @Route("/admin/organisation/{urlid}", name="superadmin_organisation")
     */
    public function adminOrganisationAction($urlid)
    {

        return $this->render('superadmin/organisation.html.twig', [
            'organisation' => organisation
        ]);
    }

    /**
     * @Route("/admin/vacancy/{urlid}", name="superadmin_vacancy")
     */
    public function adminVacancyAction($urlid)
    {

        return $this->render('superadmin/vacancy.html.twig', [
            'vacancy' => vacancy
        ]);
    }

}
