<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Vacancy;
use AppBundle\Entity\Candidacy;
use AppBundle\Entity\DigestEntry;
use AppBundle\Entity\Form\VacancyType;
use AppBundle\Controller\UtilityController;

class VacancyController extends UtilityController
{
    /**
     * @Route("/vacature/pdf/{urlid}", name="vacancy_pdf_by_urlid")
     */
    public function createPdfAction($title)
    {
        $t = $this->get('translator');
        $vacancy = $this->getVacancyRepository()->findOneByUrlid($title);
        if ($vacancy) {
            $pdf = new \FPDF_FPDF("P", "pt", "A4");
            $pdf->AddPage();
            $pdf->SetFont("Times", "B", 12);
            $pdf->Cell(0, 10, $vacancy->getTitle(), 0, 2, "C");
            $pdf->MultiCell(0, 20, "Beschrijving: \t".
                $vacancy->getDescription());
            $pdf->MultiCell(0, 20, "Organisatie: \t".
                $vacancy->getOrganisation()->getStreet(), 0, "L");
            $pdf->MultiCell(0, 20, "Locatie: \t", 0, "L");
            $pdf->Output();
            return $this->render($pdf->Output());
        } else
            throw new \Exception($t->trans('vacancy.createPdf.exception'));
    }

    /**
     * Controller to give the user a choice of what kind of vacancy he wants to create.
     * @Route("/vacature/start", name="start_vacancy")
     */
    public function startVacancyAction(Request $request)
    {
        $user = $this->getUser();
        $authenticationUtils = $this->get("security.authentication_utils");
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        if($user){
            $organisations = $user->getOrganisations()->filter(
                function($org){
                    return !$org->getDeleted();
                }
            );
        }
        else
        {
            $organisations = null;
        }

        return $this->render("organisation/vrijwilliger_vinden.html.twig",
                [
                    "organisations" => $organisations,
                    "last_username" => $lastUsername,
                    "error"         => $error,
                    "path" => "start_vacancy",
                ]);
    }

    /**
     * @Security("has_role('ROLE_USER')") //TODO: apply correct role
     * @Route("/vacature/nieuw", name="create_vacancy")
     * @Route("/{organisation_urlid}/vacature/nieuw", name="create_vacancy_for_organisation")
     */
    public function createVacancyAction(Request $request, $organisation_urlid = null)
    {
        $t = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        if($organisation_urlid){
            $user = $this->getUser();
            $organisation = $em->getRepository("AppBundle:Organisation")
                                ->findOneByUrlid($organisation_urlid);
            if(!$user->getOrganisations()->contains($organisation)){
                throw $this->createAccessDeniedException($t->trans('vacancy.create.noAdmin'));
            }
        }

        $vacancy = new Vacancy();
        $vacancy->setStartdate(new \DateTime("today"))
            ->setEnddate(new \DateTime("today"));
        $form = $this->createForm(VacancyType::class, $vacancy);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $vacancy = $form->getData();
            if (!is_null($organisation_urlid)){
                $vacancy->setOrganisation($organisation);
            }

            if($form->get('save')->isClicked()){
                $vacancy->setPublished(Vacancy::SAVED);
            }

            $this->setCoordinates($vacancy);
            $vacancy->setCreator($this->getUser());
            $em->persist($vacancy);
            $em->flush();

            //set a success message
            $this->addFlash('approve_message', $t->trans('vacancy.flash.createStart') . ' ' . $vacancy->getTitle() . ' ' . $t->trans('vacancy.flash.createEnd')
            );

            if (!is_null($organisation_urlid)){
                //set digest / send email to all administrators
                $info = array(
                            'subject' => $t->trans('vacancy.mail.create'),
                            'template' => 'vacature_aangemaakt.html.twig',
                            'txt/plain' => 'vacature_aangemaakt.txt.twig',
                            'to' => $user->getEmail(),
                            'data' => array(
                                'user' => $user,
                                'vacancy' => $vacancy,
                                'org' => $organisation,
                            ),
                            'event' => DigestEntry::NEWVACANCY,
                        );
                $this->digestAndMail($info, $organisation);
            }

            return $this->redirect($this->generateUrl("vacancy_by_urlid",
            ["urlid" => $vacancy->getUrlId() ] ));

        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            //set an error message
            $this->addFlash('error', $t->trans('general.flash.formError'));
        }

        return $this->render("vacancy/vacature_nieuw.html.twig",
            [
                "form" => $form->createView(),
                "createForm" => true,
            ]);
    }

    /**
     * @Route("/vacature/{urlid}", name="vacancy_by_urlid")
     */
    public function vacancyViewAction($urlid)
    {
        $vacancy = $this->getVacancyRepository()->findOneByUrlid($urlid);
        if (!$vacancy) throw $this->createNotFoundException('Deze vacature bestaat niet');
        return $this->render("vacancy/vacature.html.twig",
            ["vacancy" => $vacancy]);
    }

    /**
     * Kandidaat stellen voor een vacature
     * @Security("has_role('ROLE_USER')")
     * @Route("/vacature/{urlid}/inschrijven", name="vacancy_subscribe")
     * @Route("/vacature/{urlid}/uitschrijven", name="vacancy_unsubscribe")
     */
    public function subscribeVacancyAction($urlid)
    {
        $t = $this->get('translator');
        $person = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $vacancy = $em->getRepository("AppBundle:Vacancy")
            ->findOneByUrlid($urlid);

        $candidacies = $em->getRepository('AppBundle:Candidacy')
            ->findBy(array(
                'candidate' => $person->getId(),
                'vacancy' => $vacancy->getId(),
                'state' => Candidacy::PENDING,
            ));

        if ($candidacies) {
            foreach ($candidacies as $candidacy) {
                $candidacy->setState(Candidacy::WITHDRAWN);
                $em->flush();
            }

            //set a success message
            $this->addFlash('approve_message', $t->trans('vacancy.flash.okRemoveSubscribe'));

            //set digest sent / send email to all administrators
            $subject = $person->getFirstname() . ' ' . $person->getLastname() .
                       ' ' . $t->trans('vacancy.mail.removeCandidacySubjectStart') . ' "' . $vacancy->getTitle() . '" ' . $t->trans('vacancy.mail.removeCandidacySubjectEnd');
            $organisation = $vacancy->getOrganisation();
            $info = array(
                        'subject' => $subject,
                        'template' => 'ranCandidate.html.twig',
                        'txt/plain' => 'ranCandidate.txt.twig',
                        'data' => array(
                            'candidate' => $person,
                            'vacancy' => $vacancy,
                            'org' => $organisation,
                        ),
                        'event' => DigestEntry::NEWCANDIDATE,
                        'sent' => true,
                    );
            $this->digestAndMail($info);
        } else {
            $candidacies = $em->getRepository('AppBundle:Candidacy')
                ->findBy(array(
                    'candidate' => $person->getId(),
                    'vacancy' => $vacancy->getId(),
                ));
            if($candidacies){
                foreach ($candidacies as $candidacy) {
                    $candidacy->setState(Candidacy::PENDING);
                }
            } else {
                $candidacy = new Candidacy();
                $candidacy->setCandidate($person)->setVacancy($vacancy);
            }

            $em->persist($candidacy);
            $em->flush();

            //set a success message
            $this->addFlash('approve_message', $t->trans('vacancy.flash.submitCandidacy'));

            //set digest / send email to all administrators
            $subject = $person->getFirstname() . ' ' . $person->getLastname() .
                       ' ' . $t->trans('vacancy.mail.submitCandidacy') . ' ' . $vacancy->getTitle();
            $organisation = $vacancy->getOrganisation();
            $info = array(
                        'subject' => $subject,
                        'template' => 'newCandidate.html.twig',
                        'txt/plain' => 'newCandidate.txt.twig',
                        'data' => array(
                            'candidate' => $person,
                            'vacancy' => $vacancy,
                            'org' => $organisation,
                        ),
                        'event' => DigestEntry::NEWCANDIDATE,
                    );
            $this->digestAndMail($info);
        }

        return $this->redirectToRoute("vacancy_by_urlid", ["urlid" => $urlid]);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/vacature/{urlid}/{saveaction}",
     *              name="vacancy_save",
     *              requirements={"saveaction": "save|remove"})
     */
    public function saveVacancyAction($urlid, $saveaction)
    {
        $t = $this->get('translator');
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $vacancy = $em->getRepository("AppBundle:Vacancy")
            ->findOneByUrlid($urlid);

        $ajax = isset($_GET['ajax']);

        // standaard unliken om geen doubles te creeren
        $user->removeLikedVacancy($vacancy);
        if ($saveaction == "save")
        {
            $user->addLikedVacancy($vacancy);
            $organisation = $vacancy->getOrganisation();

            $info = array(
                'data' => array(
                    'saver' => $user,
                    'vacancy' => $vacancy,
                    'org' => $organisation,
                ),
                'event' => DigestEntry::SAVEDVACANCY
            );
            $this->addOrSetDigestsSent($info, $organisation);

            if(!$ajax){
                //set a success message
                $this->addFlash('approve_message', $t->trans('vacancy.flash.addToSaved'));
            }
        } else {
            if(!$ajax){
                //set a success message
                $this->addFlash('approve_message', $t->trans('vacancy.flash.removeFromSaved'));
            }
        }
        $em->persist($user);
        $em->flush();


        if (!$ajax) {
            return $this->redirectToRoute("vacancy_by_urlid", ["urlid" => $urlid]);
        } else {
            if ($saveaction == "save") {
                $arResult = array(
                    "url" => $this->generateUrl('vacancy_save', array('urlid' => $urlid, "saveaction" => "remove")),
                    "class" => "liked",
                    "text" => $t->trans('vacancy.ajax.removeFromSaved'),
                );
            } else {
                $arResult = array(
                    "url" => $this->generateUrl('vacancy_save', array('urlid' => $urlid, "saveaction" => "save")),
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
     * A list of the most recently created vacancies.
     * @param  integer $nr       The amount of vacancies desired
     * @param  string  $viewMode The viewmode for the generated output
     */
    public function listRecentVacanciesAction($nr, $viewMode = 'list')
    {
        $es = $this->get("ElasticsearchQuery");

        $query = '{
                    "query": {
                        "function_score": {
                           "filter": {
                               "bool": {
                                   "must": [
                                      {
                                          "term": {"published": 1 }
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
                    "size": ' . $nr . '
                }';

        return $this->render("vacancy/vacatures_oplijsten.html.twig", [
                "vacancies" => $es->requestByType($query, 'vacancy'),
                 "viewMode" => $viewMode
         ]);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/vacature/aanpassen/{urlid}", name="vacancy_edit")
     */
    public function editVacancyAction($urlid, Request $request){
        $t = $this->get('translator');
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $vacancy = $em->getRepository("AppBundle:Vacancy")->findOneByurlid($urlid);
        if (!$vacancy) throw $this->createNotFoundException('Deze vacature bestaat niet');

        $iamadmin = false;
        if ($vacancy->getOrganisation()) if ($vacancy->getOrganisation()->getAdministrators()->contains($user)) $iamadmin = true;
        if ($vacancy->getCreator() == $user) $iamadmin = true;

        if($iamadmin){
            $form = $this->createForm(VacancyType::class, $vacancy);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $vacancy = $form->getData();
                $this->setCoordinates($vacancy);
                $em->persist($vacancy);
                $em->flush();

                //set a success message
                $this->addFlash('approve_message', $t->trans('vacancy.flash.okModification'));

                return $this->redirect($this->generateUrl("vacancy_by_urlid",
                    array("urlid" => $vacancy->getUrlId() ) ));
            }
            else if ($form->isSubmitted() && !$form->isValid())
            {
                //set an error message
                $this->addFlash('error', $t->trans('general.flash.formError'));
            }

            return $this->render("vacancy/vacature_aanpassen.html.twig",
                array("form" => $form->createView(),
                      "urlid" => $urlid) );
        }

        throw $this->createAccessDeniedException($t->trans('vacancy.exception.noAdmin'));
    }

    /**
     * Get vacancies matching a user profile
     * @param  AppBundle\Entity\Person $user the user for which the vacancies have to be retrieved
     */
    public function vacaturesOpMaatAction($user)
    {
        $t = $this->get('translator');
        $es = $this->get("ElasticsearchQuery");

        //published filter
        $query = '{
            "query": {
                "function_score": {
                    "filter": {
                      "bool": {
                        "must": [
                           { "term": { "published": 1 }}';

        if($user->getAccess()){ //if user needs access, filter out vacancies that provide access
            $query .= ',{ "term": { "access": true }}';
        }

        if(!$user->getLongterm()){ //if user does not want to do longterm volunteering, filter out all vacancies that don't require longterm volunteering
            $query .= ',{ "term": { "longterm": false }}';
        }

        if($user->getRenumerate()){ //if user wants to be payed, filter out all vacancies where pay is given
            $query .= ',{ "exists": { "field": "renumeration" }}';
        }

        //date boost
        $query .= '     ]
                      }
                    },
                    "functions": [
                        {
                            "gauss":{
                                "startdate":{
                                    "origin": "now",
                                    "offset": "4w",
                                    "scale": "4w"
                                }
                            }
                        }';

        if($user->getLatitude() && $user->getLongitude()){ //proximity boost
            $query .= ',{
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
                        }';
        }

        $estimatedWorkInHours = $user->getEstimatedWorkInHours();
        if($estimatedWorkInHours > 0){ //boost on work time
            $query .= ',{
                            "filter": {
                                "exists": {
                                    "field": "estimatedWorkInHours"
                                }
                            },
                            "gauss":{
                                "estimatedWorkInHours":{
                                    "origin": ' . $estimatedWorkInhours / 2 . ',
                                    "offset": ' . $estimatedWorkInhours / 2 . ',
                                    "scale": 1
                                }
                            }
                        }';
        }

        //boost on likers
        $query .= ',{
                      "gauss": {
                        "likers": {
                            "origin": 50,
                            "scale": 5
                        }
                      }
                    }';

        $userSkills = $user->getSkills(); //boost on overlapping skills
        if(!is_null($userSkills) && !$userSkills->isEmpty()){
            foreach ($userSkills as $key => $skill) {
                $query .= ',{
                                "filter": {
                                    "term": {
                                       "skills.name": "' . $skill->getName() . '"
                                    }
                                },
                                "weight": 1
                            }';
            }
        }

        $query .= ',{
                        "filter": {
                            "term": {
                               "socialInteraction": "normal"
                            }
                        },
                        "weight": 2
                    }';

        $orgIds = $user->getLikedOrganisationIds(); //boost if org is liked
        if(!empty($orgIds)){
            foreach ($orgIds as $key => $id) {
                $query .= ',{
                            "filter": {
                                "term": {
                                   "organisation.id": ' . $id . '
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

        return $this->render("vacancy/vacature_tab.html.twig",
            [
                'vacancies' => $es->requestByType($query, 'vacancy'),
                'title' => $t->trans('vacancy.template.vacancyFit')
            ]);
    }

    /**
     * Get all saved vacancies for a user
     * @param  AppBundle\Entity\Person $user the user for which the vacancies have to be retrieved
     */
    public function listSavedVacanciesAction($user)
    {
        return $this->render("vacancy/vacatures_oplijsten.html.twig",
            ["vacancies" => $user->getLikedVacancies(), "viewMode" => 'tile']);
    }

    /**
     * Create a list of all vacancies that are currently open, for a given organisation
     * @param integer $id an organisation id
     */
    public function ListOrganisationVacanciesAction($id, $status = Vacancy::OPEN)
    {
        $vacancy = $this->getVacancyRepository();
        $query = $vacancy->createQueryBuilder("v")
            ->where("v.organisation = :id and v.published = :status")
            ->setParameter('id', $id)
            ->setParameter('status', $status)
            ->getQuery();

        $vacancies = $query->getResult();

        return $this->render("vacancy/vacatures_oplijsten.html.twig",
                [
                    "vacancies" => $vacancies,
                    "viewMode" => "tile",
                ]);
    }

    /**
     * Delete or restore a vacancy
     * @Route("/vacature/{urlid}/delete", name="delete_vacancy", defaults={ "deleted" = 4 })
     * @Route("/vacature/{urlid}/restore", name="restore_vacancy", defaults={ "deleted" = 1 })
     * @param  AppBundle\Entity\Vacancy $vacancy the vacancy to be deleted or restored
     */
    public function changeVacancyPublishedStatusAction($urlid, $deleted)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $vacancy = $em->getRepository("AppBundle:Vacancy")
            ->findOneByUrlid($urlid);
        if($vacancy->getOrganisation()->getAdministrators()->contains($user)){
            $vacancy->setPublished($deleted);
            $em->persist($vacancy);
            $em->flush();
        }

        return $this->redirectToRoute('vacancy_by_urlid', array('urlid' => $urlid));
    }

    private function getVacancyRepository(){
        return $this->getDoctrine()->getManager()->getRepository("AppBundle:Vacancy");
    }
}
