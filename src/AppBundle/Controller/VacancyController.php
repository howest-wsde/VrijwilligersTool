<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Vacancy;
use AppBundle\Entity\Candidacy;
use AppBundle\Entity\Form\VacancyType;

class VacancyController extends controller
{
    /**
     * @Route("/vacature/pdf/{urlid}", name="vacancy_pdf_by_urlid")
     */
    public function createPdfAction($title)
    {
        $em = $this->getDoctrine()->getManager();
        $vacancy = $em->getRepository("AppBundle:Vacancy")->findOneByUrlid($title);
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
            throw new \Exception("De gevraagde vacature bestaat niet!");
    }


    /**
     * Controller to give the user a choice of what kind of vacancy he wants to create.
     * @Security("has_role('ROLE_USER')") //TODO: apply correct role
     * @Route("/vacature/start", name="start_vacancy")
     */
    public function startVacancyAction(Request $request)
    {
        $organisations = $this->getUser()->getOrganisations();
        return $this->render("organisation/vrijwilliger_vinden.html.twig",
                ["organisations" => $organisations ]
            );
    }


    /**
     * @Security("has_role('ROLE_USER')") //TODO: apply correct role
     * @Route("/vacature/nieuw", name="create_vacancy")
     * @Route("/{organisation_urlid}/vacature/nieuw", name="create_vacancy_for_organisation")
     */
    public function createVacancyAction(Request $request, $organisation_urlid = null)
    {
        $vacancy = new Vacancy();
        $vacancy->setStartdate(new \DateTime("today"))
            ->setEnddate(new \DateTime("today"));
        $form = $this->createForm(VacancyType::class, $vacancy);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if (!is_null($organisation_urlid)){
                $organisation = $em->getRepository("AppBundle:Organisation")
                                    ->findOneByUrlid($organisation_urlid);
                $vacancy->setOrganisation($organisation);
            }

            $em->persist($vacancy);
            $em->flush();
            return $this->redirect($this->generateUrl("vacancy_by_urlid",
            ["urlid" => $vacancy->getUrlId() ] ));
        }
        return $this->render("vacancy/vacature_nieuw.html.twig",
            ["form" => $form->createView() ] );
    }

    /**
     * @Route("/vacature/{urlid}", name="vacancy_by_urlid")
     */
    public function vacancyViewAction($urlid)
    {
        $em = $this->getDoctrine()->getManager();
        $vacancy = $em->getRepository("AppBundle:Vacancy")
            ->findOneByUrlid($urlid);
        return $this->render("vacancy/vacature.html.twig",
            ["vacancy" => $vacancy]);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/vacature/{urlid}/inschrijven", name="vacancy_subscribe")
     * @Route("/vacature/{urlid}/uitschrijven", name="vacancy_unsubscribe")
     */
    public function subscribeVacancy($urlid)
    {
        $person = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $vacancy = $em->getRepository("AppBundle:Vacancy")
            ->findOneByUrlid($urlid);

        $candidacies = $em->getRepository('AppBundle:Candidacy')
            ->findBy(array('candidate' => $person->getId(), 'vacancy' => $vacancy->getId()));

        if ($candidacies) {
            foreach ($candidacies as $candidacy) {
                $em->remove($candidacy);
                $em->flush();
            }
        } else {
            $candidacy = new Candidacy();
            $candidacy->setCandidate($person)->setVacancy($vacancy);
            $em->persist($candidacy);
            $em->flush();
        }

        return $this->redirectToRoute("vacancy_by_urlid", ["urlid" => $urlid]);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/vacature/{urlid}/{likeunlike}",
     *              name="vacancy_like",
     *              requirements={"likeunlike": "like|unlike"})
     */
    public function likeVacancy($urlid, $likeunlike)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $vacancy = $em->getRepository("AppBundle:Vacancy")
            ->findOneByUrlid($urlid);
        $user->removeLikedVacancy($vacancy); // standaard unliken om geen doubles te creeren
        if ($likeunlike == "like") $user->addLikedVacancy($vacancy);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute("vacancy_by_urlid", ["urlid" => $urlid]);
    }


    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/vacature/{urlid}/goedkeuren", name="vacancy_candidacies")
     */
    //TODO: check if user is authenticated to do so aka its his vacancy
    public function vacancyCandidacies($urlid)
    {
        $em = $this->getDoctrine()->getManager();
        $vacancy = $em->getRepository("AppBundle:Vacancy")->findOneByUrlid($urlid);
        //$userId = $this->getUser()->getId();

        $approved =$em->getRepository("AppBundle:Candidacy")->findBy(array('vacancy' => $vacancy->getId(),
            'state' => Candidacy::APPROVED));

        $pending = $em->getRepository("AppBundle:Candidacy")->findBy(array('vacancy' => $vacancy->getId(),
            'state' => Candidacy::PENDING));


        return $this->render("vacancy/vacature_goedkeuren.html.twig",
            ["vacancy" => $vacancy,
             "approved" => $approved,
             "pending" => $pending]);
    }

    public function listRecentVacanciesAction($nr, $viewMode = 'list')
    {
        $vacancies = $this->getDoctrine()
                        ->getRepository("AppBundle:Vacancy")
                        ->findBy(array(), array("id" => "DESC"), $nr);
        return $this->render("vacancy/vacatures_oplijsten.html.twig",
            ["vacancies" => $vacancies, "viewMode" => $viewMode]);
    }

    public function listParentSkillsAction($nr)
    {
        $repository = $this->getDoctrine()
            ->getRepository("AppBundle:Skill");

        $query = $repository->createQueryBuilder("s")
            ->where("s.parent IS NULL")
            ->addOrderBy("s.id", "DESC")
            ->addOrderBy("s.name", "ASC")
            ->getQuery();

        $query->setMaxResults($nr);

        return $this->render("skill/recente_categorien.html.twig",
            ["skills" => $query->getResult()]);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/vacature/aanpassen/{urlid}", name="vacancy_edit")
     */
    public function editVacancyAction($urlid, Request $request){
        $em = $this->getDoctrine()->getManager();
        $vacancy = $em->getRepository("AppBundle:Vacancy")->findOneByurlid($urlid);
        $form = $this->createForm(VacancyType::class, $vacancy);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $vacancy->setTitle($data->getTitle());
            $vacancy->setDescription($data->getDescription());
            $vacancy->setEndDate($data->getEnddate());

            $em->flush();

            return $this->redirect($this->generateUrl("vacancy_by_urlid",
                array("urlid" => $vacancy->getUrlId() ) ));
        }

        return $this->render("vacancy/vacature_aanpassen.html.twig",
            array("form" => $form->createView(),
                  "urlid" => $urlid) );
    }

    public function ListOrganisationVacanciesAction($urlid)
    {
        $em = $this->getDoctrine()->getManager();
        $organisation = $em->getRepository("AppBundle:Organisation")
            ->findOneByUrlid($urlid);

        return $this->render("vacancy/vacatures_min.html.twig",
                ["vacancies" => $organisation->getVacancies()]);

    }
}
