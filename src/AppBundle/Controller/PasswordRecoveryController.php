<?php
namespace AppBundle\Controller;


use AppBundle\Entity\Form\ResetPasswordType;
use AppBundle\Entity\PasswordRecover;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Tests\Extension\Core\Type\PasswordTypeTest;
use Symfony\Component\Form\Tests\Extension\Core\Type\RepeatedTypeTest;
use Symfony\Component\Form\Tests\Extension\Core\Type\SubmitTypeTest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Person;
use AppBundle\Entity\DigestEntry;
use AppBundle\Entity\Form\PersonType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/*
 * TODO=============================
 * - VALIDATE PERSON
 * - zelfde paswoord CONSTRAINTS als person toevoegen
 * - vertalingen implementeren
 * - implementatie voor enkel email
 * - evt fancy html mail maken?
 * - change route to paswoord/trecover/hash
 * - duplicate error fix --> check if already in db on request
 * - reuse existing form for resetting?
 * */

class PasswordRecoveryController extends Controller
{
    /**
     * @Route("/paswoord/recover/", name="request_recover")
     */
    //REQUEST OF PASSWORD RESET
    public function requestReset(Request $request){ //build form and submit

        $form = $this->createFormBuilder()
            ->add('logincredential', TextType::class,array(
                'label' => 'Telefoon of email-adres',
                'invalid_message' => 'vul een geldig email of telefoonnummer in!',
                'required' => true))
            ->add('save', SubmitType::class, array('label' => 'Versturen'))
            ->getForm();

        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){
            $loginCredential = $form['logincredential']->getData();

            $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder();

            $qb ->select('person')
                ->from('AppBundle:Person', 'person')
                ->where('person.email = ?1')
                ->orWhere('person.telephone = ?2')
                ->setParameter(1,$loginCredential)
                ->setParameter(2,$loginCredential);

            $user = $qb->getQuery()->getResult();
            $user = array_pop($user);

            if(!is_null($user)){
                $this->recoverAction($user);
                $this->addFlash('success', 'er werd een mail gestuurd naar het ingevulde adres. Volg de link in de mail om uw paswoord te resetten');
            }else{
                $this->addFlash('error', 'dit emailadres of telefoonnummer werd niet gevonden.');
                //dit moet beter geschreven worden, temporary + vertaling
            }
            return $this->render('passwordrecovery/password_recovery_submit_status.html.twig');//submitted,show status
        }

        return $this->render('passwordrecovery/recover_form.html.twig', array(//show recover form
            'form' => $form->createView(),
        ));
    }


    // GETS CALLED BY REQUEST AND INSERTS INTO DB AND SENDS MAIL
    public function recoverAction(Person $user){
        $reset = new PasswordRecover($user);

        $em = $this->getDoctrine()->getManager();

        $em->persist($reset);
        $em->flush();

        $message = \Swift_Message::newInstance()
            ->setSubject('roeselarevrijwilligt.be wachtwoord reset')
            ->setFrom('reset@roeselarevrijwilligt.be')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'passwordrecovery/reset_email.html.twig',
                    array('user' => $reset)
                ),
                'text/plain'
            );

        $this->get('mailer')->send($message);
    }



    //HANDLES THE VALIDATION OG HASH URL AND SHOWS FORM PASSWD CHANGE
    //GET CURRENT TIME AND CHECK
    /**
     * @Route("/paswoord/{hash}", name="password_recover")
     */
    public function resetForm($hash, Request $request){
        $em = $this->getDoctrine()->getEntityManager();

        $recovery = $em->getRepository("AppBundle:PasswordRecover")
                       ->findOneBy(array('hash' => $hash));


        if(!is_null($recovery)){
            if(strtotime($recovery->getExpiryDate()) >= strtotime(date("Y-m-d H:i:s"))){

                $person  = $em->getRepository("AppBundle:Person")
                    ->find($recovery->getPerson()->getId());//BETER schrijven!!

                $form = $this->createFormBuilder()//$perrson?s
                             ->add('newPassword', RepeatedType::class, array(
                                   'type' => PasswordType::class,
                                   'invalid_message' => 'De paswoorden moeten overeen komen!.',
                                   'required' => true,
                                   'first_options'  => array('label' => 'Password'),
                                   'second_options' => array('label' => 'Repeat Password'),
                                  )
                             )
                             ->add('submit', SubmitType::class)
                             ->getForm();

                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {

                    $person->setPlainPassword($form->get('newPassword')->getData());
                    $password = $this->get("security.password_encoder")
                                     ->encodePassword($person, $person->getPlainPassword());
                    $person->setPassword($password);

                    $em->persist($person);//persist new password
                    $em->remove($recovery);// remove recovery from database
                    $em->flush();

                    $this->addFlash('success', "uw paswoord werd succesvol aangepast! U kan nu inloggen met uw nieuw paswoord.");
                    return $this->render('passwordrecovery/password_recovery_submit_status.html.twig');

                }

                return $this->render('passwordrecovery/recover_form.html.twig', array(
                                     'form' => $form->createView(),
                                    ));

            }
            else{
                $this->addFlash('error', 'vraag een nieuwe link aan, deze is vervallen!');
                return $this->render('passwordrecovery/password_recovery_submit_status.html.twig');
            }
        }
        else{
            $this->addFlash('error', "Deze link is niet valid! Ofwel is deze al gebruikt geweest ofwel is deze gewoon niet gelidg!");
            return $this->render('passwordrecovery/password_recovery_submit_status.html.twig');

        }

    }

}