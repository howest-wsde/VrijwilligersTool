<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Feedback;
use Symfony\Component\HttpFoundation\Session\Session;

class FeedbackController extends controller
{
    /**
     * @Route("/feedback/post", name="feedback")
     */
    public function feedbackSendAction(Request $request)
    {

        if ($request->isMethod('POST')) {
            $feedback = new Feedback();
            $user = $this->getUser();
            $feedback->setReporter( $user);
            $feedback->setUrl($request->request->get("url"));
            $feedback->setReport($request->request->get("body") . "\n\n" . $_SERVER['REMOTE_ADDR'] . "\n\n" . $_SERVER['HTTP_USER_AGENT']);
            $feedback->setState(Feedback::REPORTED);
            $feedback->setReportdate(new \DateTime("now"));
            $em = $this->getDoctrine()->getManager();
            $em->persist($feedback);
            $em->flush();

        }
       return $this->redirect($request->server->get('HTTP_REFERER'));

    }

}
