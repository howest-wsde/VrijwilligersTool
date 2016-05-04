<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Organisation;
use AppBundle\Entity\Form\OrganisationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class OrganisationController extends controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/verenigingaanmaken", name="vereniging_aanmaken")
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
            return $this->redirect($this->generateUrl("organisation_name",
            ['name' => $organisation->getUrlId() ] ));
        }
        return $this->render("organisation\maakvereniging.html.twig",
            ["form" => $form->createView() ] );
    }

    /**
     * @Route("/vereniging/{name}" , name="organisation_name")
     */
    public function viewOrganisationByNameAction($name)
    {
        $em = $this->getDoctrine()->getManager();
        $organisation = $em->getRepository("AppBundle:Organisation")
            ->findOneByUrlid($name);
        return $this->render("organisation/vereniging.html.twig",
            ["organisation" => $organisation]);
    }
}
