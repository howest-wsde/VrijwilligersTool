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
     * @Route("/vereniging/nieuw" , name="create_organisation")
     */
    public function createOrganisationAction(Request $request)
    {
        $user = $this->getUser();
        $organisation = (new Organisation())->setCreator($user);

        $form = $this->createForm(OrganisationType::class, $organisation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($organisation);

            $user->addOrganisation($organisation);
            $em->persist($user);            

            $em->flush(); 
            return $this->redirect($this->generateUrl("create_vacancy_for_organisation", ['organisation_urlid' => $organisation->getUrlId() ]));
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


    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/vereniging/{urlid}/like", name="organisation_like")
     */
    public function likeOrganisation($urlid)
    {
        $user = $this->getUser(); 
        $em = $this->getDoctrine()->getManager();
        $organisation = $em->getRepository("AppBundle:Organisation")
            ->findOneByUrlid($urlid);
        $user->addLikedOrganisation($organisation);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute("organisation_by_urlid", ["urlid" => $urlid]);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/vereniging/{urlid}/unlike", name="organisation_unlike")
     */
    public function unlikeOrganisation($urlid)
    {
        $user = $this->getUser(); 
        $em = $this->getDoctrine()->getManager();
        $organisation = $em->getRepository("AppBundle:Organisation")
            ->findOneByUrlid($urlid);
        $user->removeLikedOrganisation($organisation);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute("organisation_by_urlid", ["urlid" => $urlid]);
    }
}
