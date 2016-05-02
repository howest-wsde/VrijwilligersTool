<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Vacancy;
use AppBundle\Entity\Form\VacancyType;

class VacancyController extends controller
{
    /**
     * @Route("/vacature/pdf/{title}" , name="vacancy_pdf")
     */
    public function createPDFAction($title)
    {
        $title = str_replace("-", " ", $title);
        $em = $this->getDoctrine()->getManager();
        $vacancy = $em->getRepository("AppBundle:Vacancy")->findOneByTitle($title);
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
     * @Security("has_role('ROLE_USER')") //TODO: apply correct role
     * @Route("/vacature/nieuw", name="create_vacancy")
     */
    public function createVacancyAction(Request $request)
    {
        $vacancy = new Vacancy();
        $vacancy->setStartdate(new \DateTime("today"))
            ->setEnddate(new \DateTime("today"));
        $form = $this->createForm(VacancyType::class, $vacancy);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($vacancy);
            $em->flush();
            return $this->redirect($this->generateUrl("vacancy_title",
            ['title' => $vacancy->getNameUrl() ] ));
        }
        return $this->render("vacancy/vacature_nieuw.html.twig",
            array("form" => $form->createView()));
    }

    /**
     * @Route("/vacature/{title}", name="vacancy_title")
     */
    public function viewVacancyTitleAction($title)
    {
        $title = str_replace("-", " ", $title);
        $em = $this->getDoctrine()->getManager();
        $vacancy = $em->getRepository("AppBundle:Vacancy")
            ->findOneByTitle($title);
        return $this->render("vacancy/vacature.html.twig", array(
            "vacancy" => $vacancy)
        );
    }

    public function listRecentVacanciesAction($nr)
    {
        $entities = $this->getDoctrine()
                        ->getRepository("AppBundle:Vacancy")
                        ->findBy(array(), array("id" => "DESC"), $nr);
        return $this->render("vacancy/recente_vacatures.html.twig",
            array("vacancies" => $entities)
        );
    }
}
