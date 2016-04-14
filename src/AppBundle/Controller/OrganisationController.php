<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class OrganisationController extends controller
{
    /**
     * @Route("/vereniging/{id}" , name="vereniging_id")
     */
    public function ViewOrganisationAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $organisation = $em->getRepository('AppBundle:Organisation')
            ->findOneById($id);

        return $this->render('Organisation/vereniging.html.twig', array(
            "organisation" => $organisation
        ));
    }
}
