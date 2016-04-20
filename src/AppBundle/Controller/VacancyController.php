<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Organisation;
use AppBundle\Entity\Vacancy;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Form\VacancyType;
use Symfony\Component\HttpFoundation\Request;

class VacancyController extends controller
{
    /**
     * @Route("/vacature/pdf/{id}" , name="vacancy_pdf")
     */
    public function createPDFAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $vacancy = $em->getRepository("AppBundle:Vacancy")->find($id);

        if ($vacancy) {
            $pdf = new \FPDF_FPDF("P", "pt", "A4");
            $pdf->AddPage();

            $pdf->SetFont("Times", "B", 12);
            $pdf->Cell(0, 10, $vacancy->getTitle(), 0, 2, "C");
            $pdf->MultiCell(0, 20, "Gecreëerd op: \t".
                $vacancy->getCreationtime()->format("Y-m-d"));
            $pdf->MultiCell(0, 20, "Beschrijving: \t".
                $vacancy->getDescription());
            $pdf->MultiCell(0, 20, "Organisatie: \t".
                $vacancy->getOrganisation()->getContact()->getAddress(), 0, "L");
            $pdf->MultiCell(0, 20, "Locatie: \t", 0, "L");
            $pdf->Output();
            return $this->render($pdf->Output());
        } else
            throw new \Exception("De gevraagde vacature bestaat niet!");
    }

    /**
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

            $this->addFlash("success-notice","Uw vacature werd correct ontvangen en opgeslagen");
            return $this->redirect($this->generateUrl("create_vacancy"));
        }

        return $this->render("vacancy/vacature_nieuw.html.twig",
            array("form" => $form->createView()));
    }

    /**
     * @Route("/vacature/{id}", name="vacancy_id")
     */
    public function viewVacancyIdAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $vacancy = $em->getRepository("AppBundle:Vacancy")
            ->findoneById($id);
        return $this->render("vacancy/vacature.html.twig",array(
            "vacature" => $vacancy)
        );
    }

    /**
     * @Route("/vacature/t/{title}", name="vacancy_title")
     */
    public function viewVacancyTitleAction($title)
    {
        $title = str_replace("-", " ", $title);
        $em = $this->getDoctrine()->getManager();
        $vacancy = $em->getRepository("AppBundle:Vacancy")
            ->findOneByTitle($title);
        return $this->render("vacancy/vacature.html.twig", array(
            "vacature" => $vacancy)
        );
    }
}
