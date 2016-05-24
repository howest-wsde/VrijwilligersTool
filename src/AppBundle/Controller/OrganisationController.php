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
     * @Route("/vereniging/{organisation_urlid}/admins/remove/{person_username}" , name="organisation_remove_admin")
     */
    public function organisationRemoveAdminAction($organisation_urlid, $person_username)
    {
        $em = $this->getDoctrine()->getManager();
        $organisation = $em->getRepository("AppBundle:Organisation")
            ->findOneByUrlid($organisation_urlid);
        $person = $em->getRepository("AppBundle:Person")
            ->findOneByUsername($person_username);
        $person->removeOrganisation($organisation); 
        $em->persist($person);
        $em->flush();

        return $this->redirectToRoute("organisation_by_urlid", ["urlid" => $organisation_urlid]);
    }


    /**
     * @Route("/vereniging/{organisation_urlid}/admins/add" , name="organisation_add_admin")
     */
    public function organisationAddAdminAction($organisation_urlid)
    {
        $request = Request::createFromGlobals();
        $personid = $request->request->get("userid");

        $em = $this->getDoctrine()->getManager();
        $organisation = $em->getRepository("AppBundle:Organisation")
            ->findOneByUrlid($organisation_urlid);
        $person = $em->getRepository("AppBundle:Person")
            ->findOneById($personid);
        $person->addOrganisation($organisation); 
        $em->persist($person);
        $em->flush(); 
       
        return $this->redirectToRoute("organisation_by_urlid", ["urlid" => $organisation_urlid]);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/vereniging/{urlid}/{likeunlike}",
     *              name="organisation_like",
     *              requirements={"likeunlike": "like|unlike"})
     */
    public function likeOrganisation($urlid, $likeunlike)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $organisation = $em->getRepository("AppBundle:Organisation")
            ->findOneByUrlid($urlid);
        if ($likeunlike == "like") {
            $user->addLikedOrganisation($organisation);
        } else {
            $user->removeLikedOrganisation($organisation);
        }
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute("organisation_by_urlid", ["urlid" => $urlid]);
    }
    /**
     * Function called from a twig template (base) in order to show a list of recent organisations.
     * @param  int $nr the amount of organisations to be listed
     * @return html     a html-encoded list of recent organisations
     */
    public function listRecentOrganisationsAction($nr)
    {
        $organisations = $this->getDoctrine()
            ->getRepository("AppBundle:Organisation")
            ->findBy(array(), array('id' => 'DESC'), $nr);
        return $this->render('organisation/recente_verenigingen.html.twig',
            ['organisations' => $organisations]);
    }
}
