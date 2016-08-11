<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DigestEntry;
use AppBundle\Entity\Organisation;
use AppBundle\Entity\Form\OrganisationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class OrganisationController extends UtilityController
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/vereniging/nieuw" , name="create_organisation", defaults={"urlid" = false})
     * @Route("/vereniging/nieuw/{urlid}" , name="create_organisation_step2")
     */
    public function createOrganisationAction($urlid, Request $request)
    {
        $user = $this->getUser();
        if ($urlid){
            $em = $this->getDoctrine()->getManager();
            $organisation = $em->getRepository("AppBundle:Organisation")
                ->findOneByUrlid($urlid);
        } else {
            $organisation = (new Organisation())->setCreator($user);
        }

        $form = $this->createForm(OrganisationType::class, $organisation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($organisation);

            $user->removeOrganisation($organisation); // verwijderen om geen dubbel key te hebben op volgende lijn
            $user->addOrganisation($organisation);
            $em->persist($user);

            $em->flush();

            if(!$urlid){
                //set a success message
                $this->addFlash('approve_message', 'Een nieuwe organisatie met naam ' . $organisation->getName() . ' werd aangemaakt.'
                );
            }
            else
            {
               //set a success message
                $this->addFlash('approve_message', 'De extra informatie werd succesvol opgeslagen.'
                );
            }

            return $this->redirect($this->generateUrl("create_organisation_step2", ['urlid' => $organisation->getUrlId() ]));
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            //set an error message
            $this->addFlash('error', 'U vergat een veld of gaf een foutieve waarde in voor één van de velden.  Gelieve het formulier na te kijken en bij het veld waar de foutmelding staat de nodige stappen te ondernemen.'
            );
        }

        return $this->render("organisation\maakvereniging.html.twig",
            [
                "form" => $form->createView(),
                "organisation" => ($urlid?$organisation:FALSE), // == ORGANISATION or === FALSE
            ]);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/vereniging/{urlid}/edit" , defaults={"urlid" = false}, name="organisation_edit")
     */
    public function editOrganisationAction($urlid, Request $request)
    {
        $user = $this->getUser();

        if(!$urlid){
            throw $this->createNotFoundException("De organisatie met id " . $urlid . "werd niet teruggevonden");
        }

        $em = $this->getDoctrine()->getManager();
        $organisation = $em->getRepository("AppBundle:Organisation")
            ->findOneByUrlid($urlid);

        if(!$organisation){
            throw $this->createNotFoundException("De organisatie werd niet teruggevonden");
        }

        if(!$user->getOrganisations()->contains($organisation)){
            throw $this->createAccessDeniedException("U bent geen beheerder van deze organisatie.");
        }

        $form = $this->createForm(OrganisationType::class, $organisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($organisation);
            $em->flush();

           //set a success message
            $this->addFlash('approve_message', 'De wijzigingen werden succesvol opgeslagen.');


            return $this->render("organisation/vereniging.html.twig",
            [
                "organisation" => $organisation,
                "form" => $this->createAddAdminData($urlid)['form']->createView(),
            ]);
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            //set an error message
            $this->addFlash('error', 'U vergat een veld of gaf een foutieve waarde in voor één van de velden.  Gelieve het formulier na te kijken en bij het veld waar de foutmelding staat de nodige stappen te ondernemen.'
            );
        }

        return $this->render("organisation/vereniging_aanpassen.html.twig",
            [
                "form" => $form->createView(),
                "organisation" => $organisation,
            ]);
    }

    /**
     * @Route("/vereniging/{urlid}" , name="organisation_by_urlid")
     */
    public function organisationViewAction($urlid, Request $request)
    {
        //TODO replace with AddAdminType form
        $data = $this->createAddAdminData($urlid);
        $form = $data['form'];
        $organisation = $data['organisation'];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //get the person and add him as administrator
            $em = $data['em'];
            $userId = $request->request->get('form')['addAdmin'];
            $person = $em->getRepository("AppBundle:Person")->findOneById($userId);
            $person->addOrganisation($organisation);
            $organisation->addAdministrator($person);
            $em->persist($person);
            $em->flush();
            $form = $this->createAddAdminData($urlid)['form'];

            //set digest / send email to all administrators
            $subject = $person->getFirstname() . ' ' . $person->getLastname() .
                       ' werd toegevoegd als admin voor ' . $organisation->getName();
            $info = array(
                        'subject' => $subject,
                        'template' => 'newAdmin.html.twig',
                        'txt/plain' => 'newAdmin.txt.twig',
                        'data' => array(
                            'newAdmin' => $person,
                            'org' => $organisation,
                        ),
                        'event' => DigestEntry::NEWADMIN,
                    );
            $this->digestOrMail($info);

           //set a success message
            $this->addFlash('approve_message', $person->getFirstname() . ' ' . $person->getLastname() . ' werd succesvol toegevoegd als beheerder voor deze organisatie.');
        }

        return $this->render("organisation/vereniging.html.twig",
            [
                "organisation" => $organisation,
                "form" => $form->createView(),
            ]);
    }

    /**
     * Delete or restore an organisation
     * @Route("/vereniging/{urlid}/delete", name="delete_organisation", defaults={ "deleted" = true })
     * @Route("/vereniging/{urlid}/restore", name="restore_organisation", defaults={ "deleted" = false })
     * @param  AppBundle\Entity\Organisation $organisation the organisation to be deleted or restored
     */
    public function changeOrganisationDeletedStatusAction($urlid, $deleted)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $organisation = $em->getRepository("AppBundle:Organisation")
            ->findOneByUrlid($urlid);
        if($organisation->getAdministrators()->contains($user)){
            $organisation->setDeleted($deleted);
            $em->persist($organisation);
            $em->flush();
        }

        return $this->redirectToRoute('organisation_by_urlid', array('urlid' => $urlid));
    }

    /**
     * @Route("/vereniging/{organisation_urlid}/admins/remove/{person_username}" , name="organisation_remove_admin")
     */
    public function organisationRemoveAdminAction($organisation_urlid, $person_username)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $organisation = $em->getRepository("AppBundle:Organisation")
            ->findOneByUrlid($organisation_urlid);
        $person = $em->getRepository("AppBundle:Person")
            ->findOneByUsername($person_username);
        if($organisation->getAdministrators()->contains($user)){
            $person->removeOrganisation($organisation);
            $em->persist($person);
            $em->flush();

            //set digest / send email to all administrators
            $subject = $person->getFirstname() . ' ' . $person->getLastname() .
                       ' werd verwijderd als beheerder voor ' . $organisation->getName();
            $info = array(
                        'subject' => $subject,
                        'template' => 'removeAdmin.html.twig',
                        'txt/plain' => 'removeAdmin.txt.twig',
                        'data' => array(
                            'newAdmin' => $person,
                            'org' => $organisation,
                        ),
                        'event' => DigestEntry::NEWADMIN,
                        'remove' => true,
                    );
            $this->digestOrMail($info);

           //set a success message
            $this->addFlash('approve_message', $person->getFirstname() . ' ' . $person->getLastname() . ' werd succesvol verwijderd als beheerder voor deze organisatie.');
        }

        return $this->redirectToRoute("organisation_by_urlid", ["urlid" => $organisation_urlid]);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/vereniging/{urlid}/{saveaction}",
     *              name="organisation_save",
     *              requirements={"saveaction": "save|remove"})
     */
    public function saveOrganisationAction($urlid, $saveaction, Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $organisation = $em->getRepository("AppBundle:Organisation")
            ->findOneByUrlid($urlid);
        $user->removeLikedOrganisation($organisation); // standaard unliken om geen doubles te creeren

        $ajax = isset($_GET['ajax']);

        if ($saveaction == "save")
        {
            if(!$ajax)
            {
               //set a success message
                $this->addFlash('approve_message', 'Deze organisatie werd toegevoegd aan uw bewaarde organisaties.');
            }
            $user->addLikedOrganisation($organisation);
        }
        else {
            if(!$ajax)
            {
               //set a success message
                $this->addFlash('approve_message', 'Deze organisatie werd verwijderd uit uw bewaarde organisaties.');
            }
        }
        $em->persist($user);
        $em->flush();

        if (!$ajax) {
            return $this->redirectToRoute("organisation_by_urlid", ["urlid" => $urlid]);
        } else {
            if ($saveaction == "save") {
                $arResult = array(
                    "url" => $this->generateUrl('organisation_save', array('urlid' => $urlid, "saveaction" => "remove")),
                    "class" => "liked",
                    "text" => "Verwijder uit bewaarde organisaties",
                );
            } else {
                $arResult = array(
                    "url" => $this->generateUrl('organisation_save', array('urlid' => $urlid, "saveaction" => "save")),
                    "class" => "notliked",
                    "text" => "Bewaar",
                );
            }
            $response = new Response();
            $response->setContent(json_encode($arResult));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }
    /**
     * Function called from a twig template (base) in order to show a list of recent organisations.
     * @param  int $nr the amount of organisations to be listed
     * @return html     a html-encoded list of recent organisations
     */
    public function listRecentOrganisationsAction($nr, $viewMode = "list")
    {
        $organisations = $this->getDoctrine()
            ->getRepository("AppBundle:Organisation")
            ->findBy(array(), array('id' => 'DESC'), $nr);
        return $this->render('organisation/verenigingen_oplijsten.html.twig',
            ['organisations' => $organisations, 'viewMode' => $viewMode]);
    }

    /**
     * Get all saved organisations for a user
     * @param  AppBundle\Entity\Person $user the user for which the organisations have to be retrieved
     */
    public function listSavedOrganisationsAction($user)
    {
        return $this->render("oranisation/verenigingen_oplijsten.html.twig",
            ["organisations" => $user->getLikedOrganisations(), "viewMode" => 'tile']);
    }

    /**
     * Get all own organisations for a user
     * @param  AppBundle\Entity\Person $user the user for which the organisations have to be retrieved
     */
    public function listOwnOrganisationsAction($user)
    {
        return $this->render("oranisation/verenigingen_oplijsten.html.twig",
            ["organisations" => $user->getOrganisations(), "viewMode" => 'tile']);
    }


    /**
     * Get a random selection of organisations.
     * @param integer $nr       The amount of organisations in the selection
     * @param string  $viewMode The way the organisations should be rendered
     */
    public function ListRandomOrganisationsAction($nr, $viewMode = "list")
    {
        $em = $this->getDoctrine()->getManager();

        $count = $this->get('doctrineUtils')->getCount($em, 'AppBundle:Organisation');
        $uniqueIntegers = $this->get('random')
                          ->generateRandomUniqueIntegerArray(1, $count, $nr);
        $query = $em->createQuery("select o from AppBundle:Organisation o where o.id in (:array)")
                    ->setParameter('array', $uniqueIntegers);

        return $this->render('organisation/verenigingen_oplijsten.html.twig',
            [
                'organisations' => $query->getResult(),
                'viewMode' => $viewMode
            ]);
    }

    /**
     * Get a listing of the amount of volunteers an organisation has - that are known on this site.
     * @param integer $id the organisation id
     */
    public function ListOrganisationVolunteersAction($id){
        $em = $this->getDoctrine()->getManager();
        $organisation = $em->getRepository("AppBundle:Organisation");

        $query = $em->createQuery("select count(c) from AppBundle:Candidacy c
                 where c.vacancy in (select distinct v.id from AppBundle:Vacancy
                  v where v.organisation = :id)")
                 ->setParameter('id', $id);

        $count = $query->getResult()[0][1];
        $count += sizeof($organisation->findOneById($id)->getAdministrators());

        $count <= 1 ? $response = " medewerker" : $response = " medewerkers en vrijwilligers";

        return new Response($count . $response . " op deze site");
    }

    /**
     * Helper function for viewOrganisationAction.  It creates the form needed to add admins and some helper variables.
     * @param  string $urlid the urlid of an organisation
     * @return mixed array        the form, the organisation and the entity manger
     */
    private function createAddAdminData($urlid){
        $em = $this->getDoctrine()->getManager();
        $organisation = $em->getRepository("AppBundle:Organisation")
            ->findOneByUrlid($urlid);
        $administrators = $organisation->getAdministrators()->map(function($person) {
                            return $person->getId();
                          })->toArray();

        $defaultData = array(
                           'org_id' => $organisation->getId(),
                           'administrators' => implode(",", $administrators),
                       );

        $form = $this->createFormBuilder($defaultData)
                ->add('addAdmin', EntityType::class, array(
                    'label' => 'organisation.label.addAdmin',
                    // query choices from this entity
                    'class' => 'AppBundle:Person',
                    'query_builder' => function (EntityRepository $er)
                    use ($defaultData) {
                        return $er->createQueryBuilder('p')
                            ->where('p.id not in (' . $defaultData['administrators']
                                . ')')
                            ->andWhere('p.username != :empty')->setParameter('empty', serialize([]))
                            ->andWhere('p.username != :null')->setParameter('null', 'N;')
                            ->orderBy('p.username', 'ASC');
                    },
                    // use the name property as the visible option string
                    'choice_label' => 'username',
                    // render as select box
                    'expanded' => false,
                    'multiple' => false,
                    'required' => false,
                    'placeholder' => false,
                ))
                ->add("submitAdmin", SubmitType::class, array(
                    "label" => "organisation.label.submitAdmin",
                    "validation_groups" => false,
                ))
                ->getForm();

        return array(
            'form' => $form,
            'organisation' => $organisation,
            'em' => $em,
        );
    }

}
