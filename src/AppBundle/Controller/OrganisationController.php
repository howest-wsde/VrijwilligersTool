<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Organisation;
use AppBundle\Entity\Form\OrganisationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Form\VacancyType;
use Symfony\Component\HttpFoundation\Request;

class OrganisationController extends controller
{
    /**
     * @Route("/vereniging/n/{name}" , name="organisation_name")
     */
    public function viewOrganisationNameAction($name)
    {
        $em = $this->getDoctrine()->getManager();
        $organisation = $em->getRepository("AppBundle:Organisation")
            ->findOneByName($name);

        return $this->render("Organisation/vereniging.html.twig", array(
            "organisation" => $organisation
        ));
    }

    /**
     * @Route("/vereniging/nieuw" , name="create_organisation")
     */
    public function createOrganisationAction(Request $request)
    {
        $organisation = new Organisation();
        $form = $this->createForm(OrganisationType::class, $organisation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($organisation);
            $em->flush();

            $this->addFlash("success-notice","Uw vereniging werd correct ontvangen en opgeslagen");
            return $this->redirect($this->generateUrl("create_organisation"));
        }

        return $this->render("organisation\maakvereniging.html.twig", array(
            "form" => $form->createView()
        ));
    }
}
