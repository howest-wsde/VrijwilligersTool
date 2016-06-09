<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request; 

class FeedbackController extends controller
{
    /**
     * @Route("/feedback/post", name="feedback")
     */
    public function feedbackSendAction(Request $request)
    { 

        if ($request->isMethod('POST')) { 
            $strURL = $request->request->get("url"); 
        }

       return $this->redirect($request->server->get('HTTP_REFERER'));
        
    }
 
}
