<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UtilityController extends Controller
{
    protected function sendMail($user, $info)
    {
        $email = array_key_exists('to', $info) ? $info['to'] : $user->getEmail();
        $subject = array_key_exists('subject', $info) ? $info['subject'] : 'Mail vanwege Roeselare Vrijwilligt';
        $from = array_key_exists('from', $info) ? $info['from'] : 'info@roeselareVrijwilligt.be';
        $datatxt = array_key_exists('datatxt', $info) ? $info['datatxt'] : $info['data'];
        $template = 'email/' . $info['template'];
        $txtTemplate = 'email/' . $info['txt/plain'];

        $message = \Swift_Message::newInstance()
        ->setSubject($subject)
        ->setFrom($from)
        ->setTo($email)
        ->setBody(
            $this->renderView(
                // app/Resources/views/email/template-name
                $template,
                $info['data']
            ),
            'text/html'
        );

        // If a plaintext version of the message is also available
        if($info['txt/plain'])
        {
            $message->addPart(
                $this->renderView(
                    $txtTemplate,
                    $datatxt
                ),
                'text/plain'
            );
        }

        $this->get('mailer')->send($message);
    }
}
