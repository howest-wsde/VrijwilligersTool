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

        if($request->request->has("approve")) {
            $candidacy->setState(Candidacy::APPROVED);
            $em->persist($candidacy);
            $em->flush();

            //get vacancy for this candidacy and use reduceByOne method to both
            //subtract one from stillWanted and close the vacancy if need be
            //then persist the vacancy
            $vacancy = $candidacy->getVacancy()->reduceByOne();
            $em->persist($vacancy);
            $em->flush();

            //set digest / send email to all administrators
            $person = $candidacy->getCandidate();
            $organisation = $vacancy->getOrganisation();
            $subject = $person->getFirstname() . ' ' . $person->getLastname() .
                       ' ' . $t->trans('candidacy.mail.approve');
            $info = array(
                        'subject' => $subject,
                        'template' => 'approvedCandidate.html.twig',
                        'txt/plain' => 'approvedCandidate.txt.twig',
                        'data' => array(
                            'candidate' => $person,
                            'org' => $organisation,
                            'vacancy' => $vacancy,
                        ),
                        'event' => DigestEntry::APPROVECANDIDATE,
                    );
            $this->digestOrMail($info);

            $this->addFlash('approve_message', $candidacy->getCandidate()->getFirstname() . " " . $candidacy->getCandidate()->getLastname() .
                $t->trans('candidacy.flash.approve') .
                $candidacy->getVacancy()->getTitle() . "."
            );
        }
        else if($request->request->has("cancel")){
            $candidacy->setState(Candidacy::DECLINED);
            $em->persist($candidacy);
            $em->flush();

            //set digest / send email to all administrators
            $person = $candidacy->getCandidate();
            $vacancy = $candidacy->getVacancy();
            $organisation = $vacancy->getOrganisation();
            $subject = $person->getFirstname() . ' ' . $person->getLastname() .
                       ' ' . $t->trans('candidacy.mail.disapprove');
            $info = array(
                        'subject' => $subject,
                        'template' => 'disapprovedCandidate.html.twig',
                        'txt/plain' => 'disapprovedCandidate.txt.twig',
                        'data' => array(
                            'candidate' => $person,
                            'org' => $organisation,
                            'vacancy' => $vacancy,
                        ),
                        'event' => DigestEntry::APPROVECANDIDATE,
                        'remove' => true,
                    );
            $this->digestOrMail($info);

            $this->addFlash('cancel_message', $candidacy->getCandidate()->getFirstname() .
                            " " .
                            $candidacy->getCandidate()->getLastname() .
                            $t->trans('candidacy.flash.disapprove') .
                            $candidacy->getVacancy()->getTitle() . "."
                        );
        }
        else if($request->request->has("remove")){
            $candidacy->setState(Candidacy::DECLINED);
            $vacancy = $candidacy->getVacancy()->increaseByOne();
            $em->persist($candidacy);
            $em->persist($vacancy);
            $em->flush();

            //set digest / send email to all administrators
            $person = $candidacy->getCandidate();
            $organisation = $vacancy->getOrganisation();
            $subject = $person->getFirstname() . ' ' . $person->getLastname() .
                       ' ' . $t->trans('candidacy.mail.remove');
            $info = array(
                        'subject' => $subject,
                        'template' => 'removedVolunteer.html.twig',
                        'txt/plain' => 'removedVolunteer.txt.twig',
                        'data' => array(
                            'candidate' => $person,
                            'org' => $organisation,
                            'vacancy' => $vacancy,
                        ),
                        'event' => DigestEntry::REMOVECANDIDATE,
                    );
            $this->digestOrMail($info);

            $this->addFlash('cancel_message', $candidacy->getCandidate()->getFirstname() .
                            " " .
                            $candidacy->getCandidate()->getLastname() .
                            $t->trans('candidacy.flash.remove') .
                            $candidacy->getVacancy()->getTitle() . "."
                        );
        }


        return $this->redirect($request->headers->get('referer')); //return to sender -Elvis Presley, 1962
    }
}
