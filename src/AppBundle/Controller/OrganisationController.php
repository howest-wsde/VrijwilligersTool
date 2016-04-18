<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Organisation;
use AppBundle\Entity\Vacancy;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Form\VacancyType;
use Symfony\Component\HttpFoundation\Request;

class OrganisationController extends controller
{
    /**
     * @Route("/vereniging/{id}" , name="organisation_id")
     */
    public function viewOrganisationIdAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $organisation = $em->getRepository('AppBundle:Organisation')
            ->findOneById($id);

        return $this->render('Organisation/vereniging.html.twig', array(
            "organisation" => $organisation
        ));
    }

    /**
     * @Route("/vereniging/n/{name}" , name="organisation_name")
     */
    public function viewOrganisationNameAction($name)
    {
        $em = $this->getDoctrine()->getManager();
        $organisation = $em->getRepository('AppBundle:Organisation')
            ->findOneByName($name);

        return $this->render('Organisation/vereniging.html.twig', array(
            "organisation" => $organisation
        ));
    }
}
