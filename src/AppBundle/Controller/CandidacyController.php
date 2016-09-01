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
     * @Route("/kandidatuur/{candidacyId}/{action}",
     *              name="approve_candidacy",
     *              requirements={"action": "approve|cancel|remove"} )
     */
    public function approveCandidacy(Request $request, $candidacyId, $action)
    {
        $t = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("AppBundle:Candidacy");
        $candidacy = $repository->findOneById($candidacyId);
        $person = $candidacy->getCandidate();
        $vacancy = $candidacy->getVacancy();
        $fullname = $person->getFullName();
        $info = [];

        if($action == "approve") { //kandidaat goedkeuren
            //use reduceByOne method to both subtract one from stillWanted and
            //close the vacancy if need be, then persist the vacancy
            $vacancy->reduceByOne();
            $em->persist($vacancy);
            $candidacy->setState(Candidacy::APPROVED);
            $em->persist($candidacy);
            $em->flush();

            //set digest / send email to all administrators & to candidate
            $subject = $fullname . ' ' . $t->trans('candidacy.mail.approve');
            $this->sendDigestOrMail($person, $vacancy, $subject, 'approvedCandidate', DigestEntry::APPROVECANDIDATE);
            $this->sendDigestOrMail($person, $vacancy, $subject,
                                'notifyCandidateApproved', false, false, false);

            $this->addFlash('approve_message', $fullname . $t->trans('candidacy.flash.approve') . $vacancy->getTitle() . "."
            );
        }
        else if($action == "cancel"){ //kandidaat afwijzen
            $candidacy->setState(Candidacy::DECLINED);
            $em->persist($candidacy);
            $em->flush();

            //set digest / send email to all administrators & candidate
            $subject = $fullname . ' ' . $t->trans('candidacy.mail.disapprove');
            $this->sendDigestOrMail($person, $vacancy, $subject, 'disapprovedCandidate', DigestEntry::APPROVECANDIDATE, true);
            $this->sendDigestOrMail($person, $vacancy, $subject,
                                'notifyCandidateDisapproved', false, false, false);

            $this->addFlash('cancel_message', $fullname .
                            $t->trans('candidacy.flash.disapprove') .
                            $vacancy->getTitle() . "."
                        );
        }
        else if($action == "remove"){ //actieve vrijwilliger verwijderen
            $candidacy->setState(Candidacy::REMOVED);
            $vacancy->increaseByOne();
            $em->persist($candidacy);
            $em->persist($vacancy);
            $em->flush();

            //set digest / send email to all administrators & volunteer
            $subject = $fullname . ' ' . $t->trans('candidacy.mail.remove');
            $this->sendDigestOrMail($person, $vacancy, $subject, 'removedVolunteer', DigestEntry::REMOVECANDIDATE);
            $this->sendDigestOrMail($person, $vacancy, $subject,
                                'notifyVolunteerRemoved', false, false, false);


            $this->addFlash('cancel_message', $fullname .
                            $t->trans('candidacy.flash.remove') .
                            $vacancy->getTitle() . "."
                        );
        }

        return $this->redirect($request->headers->get('referer')); //return to sender -Elvis Presley, 1962
    }

    private function sendDigestOrMail($person, $vacancy, $subject, $template, $event, $remove = false, $digest = true){
        $info = [
            'subject' => $subject,
            'template' => $template . '.html.twig',
            'txt/plain' => $template . '.txt.twig',
            'event' => $event,
            'remove' => $remove,
            'data' => array(
                'candidate' => $person,
                'org' => $vacancy->getOrganisation(),
                'vacancy' => $vacancy,
            )
        ];

        $digest ? $this->digestOrMail($info) : $this->sendMail($person, $info);
    }
}
