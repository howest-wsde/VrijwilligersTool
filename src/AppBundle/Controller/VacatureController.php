<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class VacatureController extends controller
{
    /**
     * @Route("pdf/{id}" , name="create_vacancy_pdf")
     */
    public function createPDF($id){

        $em = $this->getDoctrine()->getManager();
        $vacancy = $em->getRepository('AppBundle:Vacancy')->find($id);

       if($vacancy){
           $pdf  = new \FPDF_FPDF('P', 'pt', 'A4');
           $pdf->AddPage();

           $pdf->SetFont('Times', 'B', 12);
           $pdf->Cell(0,10,$vacancy->getTitle(), 0, 2, 'C');
           $pdf->MultiCell(0, 20, "Gecreëerd op: \t".strval($vacancy->getCreationtime()), 0, 'L');
           $pdf->MultiCell(0, 20, "Beschrijving: \t".strval($vacancy->getDescription()), 0, 'L');
           $pdf->MultiCell(0, 20, "Organisatie: \t".strval($vacancy->getOrganisation()->getContact()->getAddress()), 0, 'L');
           $pdf->MultiCell(0, 20, "Locatie: \t", 0, 'L');
           $pdf->Output();
           return $this->render($pdf->Output());
       }
       else throw new \Exception("De gevraagde vacature bestaat niet!");
    }
}