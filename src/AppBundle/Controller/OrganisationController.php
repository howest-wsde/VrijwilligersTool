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
        $t = $this->get('translator');
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
            $organisation = $form->getData();
            $this->setCoordinates($organisation);
            $em = $this->getDoctrine()->getManager();
            $em->persist($organisation);

            $user->removeOrganisation($organisation); // verwijderen om geen dubbel key te hebben op volgende lijn
            $user->addOrganisation($organisation);
            $em->persist($user);

            $em->flush();


            if(!$urlid){
                //set a success message
                $this->addFlash('approve_message', $t->trans('org.flash.createStart') . ' ' . $organisation->getName() . $t->trans('org.flash.createEnd')
                );
            }
            else
            {
               //set a success message
                $this->addFlash('approve_message', $t->trans('org.flash.saved'));
            }

            return $this->redirect($this->generateUrl("create_organisation_step2", ['urlid' => $organisation->getUrlId() ]));
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            //set an error message
            $this->addFlash('error', $t->trans('general.flash.formError'));
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
        $t = $this->get('translator');
        $user = $this->getUser();

        if(!$urlid){
            throw $this->createNotFoundException($t->trans('org.exception.notFoundStart') . " " . $urlid . $t->trans('org.exception.notFoundEnd'));
        }

        $em = $this->getDoctrine()->getManager();
        $organisation = $em->getRepository("AppBundle:Organisation")
            ->findOneByUrlid($urlid);

        if(!$organisation){
            throw $this->createNotFoundException($t->trans('org.exception.notFound'));
        }

        if(!$user->getOrganisations()->contains($organisation)){
            throw $this->createAccessDeniedException($t->trans('org.exception.noAdmin'));
        }

        $form = $this->createForm(OrganisationType::class, $organisation);
        $form->handleRequest($request);

        if ($form->isSubmitted()){
            if ($request->request->get('addadmin')) foreach ($request->request->get('addadmin') as $admin_username){
                $person = $em->getRepository("AppBundle:Person")->findOneByUsername($admin_username);
                $person->addOrganisation($organisation);
                $em->persist($person);
                $em->flush();
                //$this->addFlash('approve_message', $person->getFullName() . ' ' . $t->trans('org.flash.addAdmin'));
            }
            if ($request->request->get('removeadmin')) foreach ($request->request->get('removeadmin') as $admin_username) {
                $this->organisationRemoveAdminAction($urlid, $admin_username);
            }

            if ($form->isValid()) {
                $organisation = $form->getData();
                $this->setCoordinates($organisation);
                $em->persist($organisation);
                $em->flush();

               //set a success message
                $this->addFlash('approve_message', $t->trans('org.flash.editOk'));

                return $this->render("organisation/vereniging.html.twig",
                [
                    "organisation" => $organisation,
                    "form" => $this->createAddAdminData($urlid)['form']->createView(),
                ]);
            }
            else
            {
                //set an error message
                $this->addFlash('error', $t->trans('general.flash.formError'));
            }

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
        $t = $this->get('translator');
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
                       ' ' . $t->trans('org.mail.view') . ' ' . $organisation->getName();
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
            $this->addFlash('approve_message', $person->getFirstname() . ' ' . $person->getLastname() . ' ' . $t->trans('org.flash.newAdmin'));
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
        $t = $this->get('translator');
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $organisation = $em->getRepository("AppBundle:Organisation")
            ->findOneByUrlid($organisation_urlid);
        $person = $em->getRepository("AppBundle:Person")
            ->findOneByUsername($person_username);

        if($organisation->getAdministrators()->count() > 1){ //als niet laatste admin
            if($organisation->getAdministrators()->contains($user)){
                $person->removeOrganisation($organisation);
                $em->persist($person);
                $em->flush();

                //set digest / send email to all administrators
                $subject = $person->getFirstname() . ' ' . $person->getLastname() .
                           ' ' . $t->trans('org.mail.removeAdmin') . ' ' . $organisation->getName();
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
             //   $this->addFlash('approve_message', $person->getFullName() . ' ' . $t->trans('org.flash.removeAdmin'));
            }
        } else {
           //set a failure message
            $this->addFlash('error', $t->trans('org.flash.lastAdmin'));
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
        $t = $this->get('translator');
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
                $this->addFlash('approve_message', $t->trans('org.flash.addToSaved'));
            }
            $user->addLikedOrganisation($organisation);
        }
        else {
            if(!$ajax)
            {
               //set a success message
                $this->addFlash('approve_message', $t->trans('org.flash.removeFromSaved'));
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
                    "text" => $t->trans('org.ajax.removeFromSaved'),
                );
            } else {
                $arResult = array(
                    "url" => $this->generateUrl('organisation_save', array('urlid' => $urlid, "saveaction" => "save")),
                    "class" => "notliked",
                    "text" => $t->trans('general.label.save'),
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
        $es = $this->get("ElasticsearchQuery");

        $query = '{
                    "query": {
                        "function_score": {
                           "filter": {
                               "bool": {
                                   "must": [
                                      {
                                          "term": {"deleted": "false"}
                                      }
                                   ]
                               }
                            },
                            "functions": [
                                {
                                "random_score": {}
                                }
                            ]
                        }
                    },
                    "size":' . $nr . '
                }';

        return $this->render('organisation/verenigingen_oplijsten.html.twig',
            [
                'organisations' => $es->requestByType($query, 'organisation'),
                'viewMode' => $viewMode
            ]);
    }

    /**
     * Get a listing of the amount of volunteers an organisation has - that are known on this site.
     * @param integer $id the organisation id
     */
    public function ListOrganisationVolunteersAction($id){
        $t = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $organisation = $em->getRepository("AppBundle:Organisation");

        $query = $em->createQuery("select count(distinct c.candidate) from AppBundle:Candidacy c
                 where c.state = 1 and c.vacancy in (select distinct v.id from AppBundle:Vacancy
                  v where v.organisation = :id)")
                 ->setParameter('id', $id);

        $count = $query->getResult()[0][1];
        $count += sizeof($organisation->findOneById($id)->getAdministrators());

        $count <= 1 ? $response = " " . $t->trans('org.list.volunteer') : $response = $t->trans('org.list.volunteers');

        return new Response($count . $response . " " . $t->trans('org.list.here'));
    }

    /**
     * Get organisations matching a user profile
     * @param  AppBundle\Entity\Person $user the user for which the vacancies have to be retrieved
     */
    public function organisatiesOpMaatAction($user)
    {
        $es = $this->get("ElasticsearchQuery");

        $query = '{
            "query": {
                "function_score": {
                    "filter": {
                      "bool": {
                        "must": [
                           { "term": { "deleted": false }}';

        $query .= '     ]
                      }
                    },
                    "functions": [';

        if($user->getLatitude() && $user->getLongitude()){
            $query .= '{
                            "filter": {
                                "exists": {
                                    "field": "location"
                                }
                            },
                            "gauss":{
                                "location":{
                                    "origin": { "lat": ' . $user->getLatitude() . ', "lon": ' . $user->getLongitude() . ' },
                                    "offset": "1km",
                                    "scale": "1km"
                                }
                            },
                            "weight": 2
                        },';
        }

        $query .= '{
                      "gauss": {
                        "likers": {
                            "origin": 50,
                            "scale": 5
                        }
                      }
                    }';

        $userSectors = $user->esGetSectors();
        if(!is_null($userSectors) && !$userSectors->isEmpty()){
            foreach ($userSectors as $key => $sector) {
                $query .= ',{
                                "filter": {
                                    "term": {
                                       "sectors.name": "' . $sector->getName() . '"
                                    }
                                },
                                "weight": 1
                            }';
            }
        }

        $query .= '],
                       "score_mode": "sum"
                       }
                   }
               }';

        return $this->render("organisation/verenigingOpMaat.html.twig",
        [
            'organisations' => $es->requestByType($query, 'organisation'),
            'viewMode' => 'tile'
        ]);
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
                    'label' => 'org.label.addAdmin',
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
                    "label" => "org.label.submitAdmin",
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
