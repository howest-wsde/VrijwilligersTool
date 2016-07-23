<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UtilityController extends Controller
{
    /**
     * Send an email to the user, using the specified info.
     * @param  AppBundle::User  $user   The relevant user
     * @param  Array            $info   An array containing all needed info to send the propper mail to the propper person
     */
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

    /**
     * Process an array of administrators and apply either a sendEmail action or an addDigestEntry action.
     * @param  array    $info   All necessary info to either send an email or add a digest entry
     */
    protected function digestOrMail($info)
    {
        $org = $info['org'];
        //first take care of the admins that need mailing
        $this->sendEmails($info, $org);

        //then take care of those that need a digest entry added
        $this->addDigests($info, $org);
    }

    /**
     * Send all mails for the admins of an organisation who have chosen that
     * delivery mode
     * @param Array                     $info   All necessary information to add the correct digest entry
     * @param AppBundle::Organisation   $org    The organisation for which all admins are iterated
     */
    protected function sendEmails($info, $org)
    {
        $admins = $org->getAdministratorsByDigest(1);
        if($admins)
        {
            foreach ($admins as $admin) {
                $info['to'] = $admin->getEmail();
                $this->sendMail($admin, $info);
            }
        }

    }

    /**
     * Add all digests for the admins of an organisation who have chosen that
     * delivery mode
     * @param Array                     $info   All necessary information to add the correct digest entry
     * @param AppBundle::Organisation   $org    The organisation for which all admins are iterated
     */
    protected function addDigests($info, $org){
        for ($i = 2; $i < 6; $i++) {
            $admins = $org->getAdministratorsByDigest($i);
            if($admins){
                foreach ($admins as $admin) {
                    $info['admin'] = $admin;
                    $this->addDigestEntry($info);
                }
            }
        }
    }

    /**
     * Add the correct DigestEntry for the event.
     * @param array    $info    An array containing all the necessary data.
     */
    protected function addDigestEntry($info){
        $event = $info['event'];
        $org = $info['org'];
        $user = $info['admin'];

        switch ($event) {
            case 1: //NEWCHARGE
                $digest = new DigestEntry($event, $org, $user->getDigest(), $user, $charge);
                break;
            case 2: //NEWVACANCY
                $digest = new DigestEntry($event, $org, $user->getDigest(), $user, null, null, null, $vacancy);
                break;
            case 3: //NEWCANDIDATE
                $digest = new DigestEntry($event, $org, $user->getDigest(), $user, null, $info['candidate']);
                break;
            case 4: //NEWADMIN
                $digest = new DigestEntry($event, $org, $user->getDigest(), $user, null, null, $info['newAdmin']);
                break;
            case 5: //VACANCYFILLED
                $digest = new DigestEntry($event, $org, $user->getDigest(), $user, null, null, null, $info['vacancy']);
                break;
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($digest);
        $em->flush();
    }
}
