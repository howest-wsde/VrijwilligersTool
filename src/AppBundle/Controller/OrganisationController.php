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
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $organisation = (new Organisation())->setCreator($user);
        $form = $this->createForm(OrganisationType::class, $organisation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($organisation);
            $em->flush();
            return $this->redirect($this->generateUrl("organisation_by_urlid",
            ['urlid' => $organisation->getUrlId() ] ));
        }
        return $this->render("organisation\maakvereniging.html.twig",
            ["form" => $form->createView() ] );
    }

    /**
     * @Route("/vereniging/{urlid}" , name="organisation_by_urlid")
     */
    public function organisationViewAction($urlid)
    {
        $em = $this->getDoctrine()->getManager();
        $organisation = $em->getRepository("AppBundle:Organisation")
            ->findOneByUrlid($urlid);
        return $this->render("organisation/vereniging.html.twig",
            ["organisation" => $organisation]);
    }

    public function listRecentOrganisationsAction($nr)
    {
        $entities = $this->getDoctrine()
            ->getRepository("AppBundle:Organisation")
            ->findBy(array(), array('id' => 'DESC'), $nr);
        return $this->render('organisation/recente_verenigingen.html.twig',
            ['organisations' => $entities]);
    }
}
