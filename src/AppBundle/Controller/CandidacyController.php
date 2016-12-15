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

        if($action == "approve") { //kandidaat goedkeuren
            $this->approveCandidate($person, $candidacy, $vacancy, $em, $t);
        }
        else if($action == "cancel"){ //kandidaat afwijzen
            $this->rejectCandidate($person, $candidacy, $vacancy, $em, $t);
        }
        else if($action == "remove"){ //actieve vrijwilliger verwijderen
            $this->removeCandidate($person, $candidacy, $vacancy, $em, $t);
        }

        return $this->redirect($request->headers->get('referer')); //return to sender -Elvis Presley, 1962
    }

    private function approveCandidate($person, $candidacy, $vacancy, $em, $t){
        //use reduceByOne method to both subtract one from stillWanted and
        //close the vacancy if need be, then persist the vacancy
        $vacancy->reduceByOne();
        $em->persist($vacancy);
        $candidacy->setState(Candidacy::APPROVED);
        $em->persist($candidacy);
        $em->flush();

        $mailInfo = [
            "candidate" => [
                "subject" => $t->trans('candidacy.candidate.mail.approve'),
                "template" => "notifyCandidateApproved"
            ],
            "admins" => [
                "subject" => $t->trans('candidacy.mail.approve'),
                "template" => "approvedCandidate"
            ]
        ];

        $this->handleDigestsAndMail($person, $vacancy, $mailInfo, DigestEntry::APPROVECANDIDATE);

        $this->addFlash('approve_message', $person->getFullName() . $t->trans('candidacy.flash.approve') . $vacancy->getTitle() . "."
        );
    }

    private function rejectCandidate($person, $candidacy, $vacancy, $em, $t){
        $candidacy->setState(Candidacy::DECLINED);
        $em->persist($candidacy);
        $em->flush();

        $mailInfo = [
            "candidate" => [
                "subject" => $t->trans('candidacy.candidate.mail.disapprove'),
                "template" => "notifyCandidateDisapproved"
            ],
            "admins" => [
                "subject" => $t->trans('candidacy.mail.disapprove'),
                "template" => "disapprovedCandidate"
            ]
        ];

        $this->handleDigestsAndMail($person, $vacancy, $mailInfo, DigestEntry::DISAPPROVECANDIDATE);

        $this->addFlash('cancel_message', $person->getFullName() .
            $t->trans('candidacy.flash.disapprove') .
            $vacancy->getTitle() . "."
        );
    }

    private function removeCandidate($person, $candidacy, $vacancy, $em, $t){
        $candidacy->setState(Candidacy::REMOVED);
        $vacancy->increaseByOne();
        $em->persist($candidacy);
        $em->persist($vacancy);
        $em->flush();

        $mailInfo = [
            "candidate" => [
                "subject" => $t->trans('candidacy.candidate.mail.remove'),
                "template" => "notifyVolunteerRemoved"
            ],
            "admins" => [
                "subject" => $t->trans('candidacy.mail.remove'),
                "template" => "removedVolunteer"
            ]
        ];

        $this->handleDigestsAndMail($person, $vacancy, $mailInfo, DigestEntry::REMOVECANDIDATE);

        $this->addFlash('cancel_message', $person->getFullName() .
            $t->trans('candidacy.flash.remove') .
            $vacancy->getTitle() . "."
        );
    }

    private function handleDigestsAndMail($candidate, $vacancy, $mailInfo, $event){
        $this->handleDigestsAndMailForCandidate($candidate, $vacancy, $mailInfo["candidate"]["subject"], $mailInfo["candidate"]["template"], $event);
        $this->handleDigestsAndMailForAdmins($candidate, $vacancy, $mailInfo["admins"]["subject"], $mailInfo["admins"]["template"], $event);
    }

    private function handleDigestsAndMailForCandidate($candidate, $vacancy, $subject, $template, $event){

        $info = [
            'subject' => $subject,
            'template' => $template . '.html.twig',
            'txt/plain' => $template . '.txt.twig',
            'event' => $event,
            'isForCandidate' => true,
            'data' => array(
                'candidate' => $candidate,
                'org' => $vacancy->getOrganisation(),
                'vacancy' => $vacancy,
            )
        ];

        $this->digestAndMail($info);
    }

    private function handleDigestsAndMailForAdmins($candidate, $vacancy, $subject, $template, $event){
        $info = [
            'subject' => $subject,
            'template' => $template . '.html.twig',
            'txt/plain' => $template . '.txt.twig',
            'event' => $event,
            'isForCandidate' => false,
            'data' => array(
                'candidate' => $candidate,
                'org' => $vacancy->getOrganisation(),
                'vacancy' => $vacancy,
            )
        ];

        $this->digestAndMail($info);
    }
}
