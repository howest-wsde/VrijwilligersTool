<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\DigestEntry;

class UtilityController extends Controller
{
    /**
     * Send an email to the user, using the specified info.
     * @param  AppBundle::User  $user   The relevant user
     * @param  Array            $info   An array containing all needed info to send the propper mail to the propper person
     */
    protected function sendMail($user, $info)
    {
        $t = $this->get('translator');
        $email = array_key_exists('to', $info) ? $info['to'] : $user->getEmail();
        $subject = array_key_exists('subject', $info) ? $info['subject'] : $t->trans('utility.send.subject');
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
    protected function digestAndMail($info)
    {
        $org = $info['data']['org'];
        //first take care of the admins that need mailing
        $this->sendImmediateEmails($info, $org);

        //then take care of those that need a digest entry added/set sent
        $this->addOrSetSentRemoveDigests($info, $org);
    }

    /**
     * Send all mails for the admins of an organisation who have chosen to be
     * notified immediately (by email)
     * @param Array                     $info   All necessary information to add the correct digest entry
     * @param AppBundle::Organisation   $org    The organisation for which all admins are iterated
     */
    protected function sendImmediateEmails($info, $org)
    {
        $admins = $org->getAdministratorsByDigest(1);
        if(!is_null($admins))
        {
            foreach ($admins as $admin) {
                $info['to'] = $admin->getEmail();
                $this->sendMail($admin, $info);
            }
        }
    }

    /**
     * Adds / removes all digests for the admins of an organisation who have chosen that
     * delivery mode
     * @param Array                     $info   All necessary information to add the correct digest entry
     * @param AppBundle::Organisation   $org    The organisation for which all admins are iterated
     */
    protected function addOrSetSentRemoveDigests($info, $org){
        $sent = array_key_exists('sent', $info);
        for ($i = 2; $i < 7; $i++) {
            $admins = $org->getAdministratorsByDigest($i);
            if($admins){
                foreach ($admins as $admin) {
                    $info['admin'] = $admin;
                    $sent ? $this->setDigestEntrySent($info, $org) : $this->addDigestEntry($info, $org);
                }
            }
        }
    }

    /**
     * Add the correct DigestEntry for the event.
     * @param Array                     $info   An array containing all the necessary data.
     * @param AppBundle::Organisation   $org    The organisation for which all admins are iterated
     */
    protected function addDigestEntry($info, $org){
        $event = $info['event'];
        $user = $info['admin'];
        $vacancy = array_key_exists('vacancy', $info['data']) ? $info['data']['vacancy'] : null;
        $candidate = array_key_exists('candidate', $info['data']) ? $info['data']['candidate'] : null;
        $newAdmin = array_key_exists('newAdmin', $info['data']) ? $info['data']['newAdmin'] : null;
        $charge = array_key_exists('newCharge', $info) ? $info['newCharge'] : null;
        $saver = array_key_exists('saver', $info) ? $info['data']['saver'] : null;

        $digest = new DigestEntry($event, $org, $user->getDigest(), $user, $charge, $candidate, $newAdmin, $vacancy);

        $em = $this->getDoctrine()->getManager();
        $em->persist($digest);
        $em->flush();
    }

    /**
     * Remove the correct DigestEntry for the event.
     * @param Array                     $info   An array containing all the necessary data.
     * @param AppBundle::Organisation   $org    The organisation for which all admins are iterated
     */
    protected function setDigestEntrySent($info, $org)
    {
        $event = $info['event'];
        $user = $info['admin'];
        $vacancy = array_key_exists('vacancy', $info['data']) ? $info['data']['vacancy'] : null;
        $candidate = array_key_exists('candidate', $info['data']) ? $info['data']['candidate'] : null;
        $newAdmin = array_key_exists('newAdmin', $info['data']) ? $info['data']['newAdmin'] : null;
        $charge = array_key_exists('newCharge', $info) ? $info['newCharge'] : null;
        $saver = array_key_exists('saver', $info['data']) ? $info['data']['saver'] : null;
        $em = $this->getDoctrine()->getManager();
        $digestRepo = $em->getRepository('AppBundle:DigestEntry');

        switch ($event) {
            case 1: //NEWCHARGE
                $digests = $digestRepo->findBy(array(
                              'event' => $event,
                              'charge' => $charge,
                              'organisation' => $org,
                              'user' => $user,
                          ));
                break;

            case 2: //NEWVACANCY
                $digests = $digestRepo->findBy(array(
                              'event' => $event,
                              'vacancy' => $vacancy,
                              'user' => $user,
                          ));
                break;

            case 3: //NEWCANDIDATE
                $digests = $digestRepo->findBy(array(
                              'event' => $event,
                              'candidate' => $candidate,
                              'vacancy' => $vacancy,
                              'user' => $user,
                          ));
                break;

            case 4: //NEWADMIN
                $digests = $digestRepo->findBy(array(
                              'event' => $event,
                              'admin' => $newAdmin,
                              'organisation' => $org,
                              'user' => $user,
                          ));
                break;

            case 5: //APPROVECANDIDATE
                $digests = $digestRepo->findBy(array(
                              'event' => $event,
                              'vacancy' => $vacancy,
                              'user' => $user,
                              'candidate' => $candidate,
                          ));
                break;

            case 6: //REMOVECANDIDATE
                //hier moet niets gebeuren
                break;

            case 7: //SAVEVACANCY //TODO Create event
                $digests = $digestRepo->findBy(array(
                    'event' => $event,
                    'vacancy' => $vacancy,
                    'user' => $user,
                    'saver' => $saver,
                ));
                break;

            case 8: //SAVEORGANISATION //TODO Create event
                $digests = $digestRepo->findBy(array(
                    'event' => $event,
                    'organisation' => $org,
                    'user' => $saver,
                    'saver' => $saver,
                ));
                break;
        }

        foreach ($digests as $digest) {
            $digest->setSent(true);
            $em->flush();
        }
    }

    /**
     * Set the latitude and longitude of an entity containing those properties as well as the properties for an address
     * @param mixed $entity
     */
    protected function setCoordinates(&$entity){
      // Get JSON results from this request & convert to an array
      $geo = json_decode(file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($entity->getAddress()).'&sensor=false'), true);

      if ($geo['status'] == 'OK') {
        // Set Lat & Long
        $entity->setLatitude((string) $geo['results'][0]['geometry']['location']['lat']);
        $entity->setLongitude((string) $geo['results'][0]['geometry']['location']['lng']);
      }
    }
}
