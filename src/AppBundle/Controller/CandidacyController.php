<?php

namespace AppBundle\Controller;




use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Candidacy;
use Symfony\Component\HttpFoundation\Session\Session;

class CandidacyController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/kandidatuur/{candidacyId}/goedkeuren", name="approve_candidacy")
     */

    public function approveCandidacy(Request $request, $candidacyId)
    {
        $em = $this->getDoctrine()->getManager();
        $candidacy = $em->getRepository("AppBundle:Candidacy")->findOneById($candidacyId);

        if($request->request->has("approve")) {
            $candidacy->setState(Candidacy::APPROVED);
            $em->persist($candidacy);
            $em->flush();

            $this->addFlash('approve_message', $candidacy->getCandidate()->getFirstname() .
                            " " .
                            $candidacy->getCandidate()->getLastname() .
                            " werd goedgekeurd voor de vacature " .
                            $candidacy->getVacancy()->getTitle() . "."
                        );
        }
        else if($request->request->has("cancel")){
            $candidacy->setState(Candidacy::PENDING);
            $em->persist($candidacy);
            $em->flush();

            $this->addFlash('cancel_message', $candidacy->getCandidate()->getFirstname() .
                            " " .
                            $candidacy->getCandidate()->getLastname() .
                            " werd afgekeurd voor de vacature " .
                            $candidacy->getVacancy()->getTitle() . "."
                        );
        }

        return $this->redirect($request->headers->get('referer')); //return to sender -Elvis Presley, 1962
    }
}