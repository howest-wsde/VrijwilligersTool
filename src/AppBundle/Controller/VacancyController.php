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
        $vacancy = $em->getRepository('AppBundle:Vacancy')->find($id);

        if ($vacancy) {
            $pdf = new \FPDF_FPDF('P', 'pt', 'A4');
            $pdf->AddPage();

            $pdf->SetFont('Times', 'B', 12);
            $pdf->Cell(0, 10, $vacancy->getTitle(), 0, 2, 'C');
            $pdf->MultiCell(0, 20, "Gecreëerd op: \t".
                $vacancy->getCreationtime()->format('Y-m-d'));
            $pdf->MultiCell(0, 20, "Beschrijving: \t".
                $vacancy->getDescription());
            $pdf->MultiCell(0, 20, "Organisatie: \t".
                $vacancy->getOrganisation()->getContact()->getAddress(), 0, 'L');
            $pdf->MultiCell(0, 20, "Locatie: \t", 0, 'L');
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
        $form = $this->createForm(VacancyType::class, $vacancy);

        //TODO: check if dates are valid with constraints

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($vacancy);
                $em->flush();

                $this->addFlash('success-notice','Uw vacature werd correct ontvangen en opgeslagen, bedankt!');
                return $this->redirect($this->generateUrl('create_vacancy'));
            }
        } else {
            return $this->render('vacature/vacature_aanmaken.html.twig',
                array('form' => $form->createView()));
        }
    }

    /**
     * @Route("/vacature/{id}", name="view_vacancy")
     */
    public function viewVacancyAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $vacancy = $em->getRepository('AppBundle:Vacancy')->find($id);
        return $this->render("vacature/vacature.html.twig",array('vacature' => $vacancy));
    }
}
