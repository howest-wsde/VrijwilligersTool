<?php

namespace AppBundle\Controller;




use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Vacancy;
use AppBundle\Entity\Candidacy;
use AppBundle\Entity\Form\VacancyType;
use Symfony\Component\HttpFoundation\Response;
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
        $candidacy = $em->getRepository("AppBundle:Candidacy")->find($candidacyId);
        $candidacy->setState(1);
        $em->persist($candidacy);
        $em->flush();

        $session = $this->container->get('session');
        $session->set('approve_message', 'De persoon werd goedgekeurd voor deze vacature');
        $session->save();



        return $this->redirect($request->headers->get('referer'));
    }
}