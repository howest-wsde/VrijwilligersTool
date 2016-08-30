<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Candidacy;
use AppBundle\Entity\DigestEntry;
use Symfony\Component\HttpFoundation\Session\Session;

class CandidacyController extends UtilityController
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/kandidatuur/{candidacyId}/goedkeuren", name="approve_candidacy")
     */
    public function approveCandidacy(Request $request, $candidacyId)
    {
        $t = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("AppBundle:Candidacy");
        $candidacy = $repository->findOneById($candidacyId);
        $person = $candidacy->getCandidate();
        $vacancy = $candidacy->getVacancy();
        $organisation = $vacancy->getOrganisation();
        $fullname = $person->getFullName();

        $info = [];

        if($request->request->has("approve")) {
            //use reduceByOne method to both subtract one from stillWanted and
            //close the vacancy if need be, then persist the vacancy
            $vacancy->reduceByOne();
            $em->persist($vacancy);
            $candidacy->setState(Candidacy::APPROVED);
            $em->persist($candidacy);
            $em->flush();

            //set digest / send email to all administrators
            $info['subject'] = $fullname . ' ' . $t->trans('candidacy.mail.approve');
            $info['template'] = 'approvedCandidate.html.twig';
            $info['txt/plain'] = 'approvedCandidate.txt.twig';
            $info['event'] = DigestEntry::APPROVECANDIDATE;

            $this->addFlash('approve_message', $fullname . $t->trans('candidacy.flash.approve') . $vacancy->getTitle() . "."
            );
        }
        else if($request->request->has("cancel")){
            $candidacy->setState(Candidacy::DECLINED);
            $em->persist($candidacy);
            $em->flush();

            //set digest / send email to all administrators
            $info['subject'] = $fullname . ' ' . $t->trans('candidacy.mail.disapprove');
            $info['template'] = 'disapprovedCandidate.html.twig';
            $info['txt/plain'] = 'disapprovedCandidate.txt.twig';
            $info['event'] = DigestEntry::APPROVECANDIDATE;
            $info['remove'] = true;

            $this->addFlash('cancel_message', $fullname .
                            $t->trans('candidacy.flash.disapprove') .
                            $vacancy->getTitle() . "."
                        );
        }
        else if($request->request->has("remove")){
            $candidacy->setState(Candidacy::DECLINED);
            $vacancy->increaseByOne();
            $em->persist($candidacy);
            $em->persist($vacancy);
            $em->flush();

            //set digest / send email to all administrators
            $info['subject'] = $fullname . ' ' . $t->trans('candidacy.mail.remove');
            $info['template'] = 'removedVolunteer.html.twig';
            $info['txt/plain'] = 'removedVolunteer.txt.twig';
            $info['event'] = DigestEntry::REMOVECANDIDATE;

            $this->addFlash('cancel_message', $fullname .
                            $t->trans('candidacy.flash.remove') .
                            $vacancy->getTitle() . "."
                        );
        }

        $info['data'] = array(
            'candidate' => $person,
            'org' => $vacancy->getOrganisation(),
            'vacancy' => $vacancy,
        );

        $this->digestOrMail($info);

        return $this->redirect($request->headers->get('referer')); //return to sender -Elvis Presley, 1962
    }
}
