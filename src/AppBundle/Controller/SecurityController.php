<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Person;
use AppBundle\Entity\Form\PersonType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityController extends UtilityController
{
    /**
    * @Route("/vrijwilliger_worden", name="register_user")
    */
    public function registerAction(Request $request)
    {
        //TODO: http://symfony.com/doc/current/cookbook/doctrine/registration_form.html
        $user = new Person();
        $form = $this->createForm(PersonType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            //encode password en replace the plain password to the encoded one
            $password = $this->get("security.password_encoder")
                             ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            //persist the user to the dbase
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            //log in the user as far as Symfony is concerned
            $token = new UsernamePasswordToken($user, $password, 'main', array('ROLE_USER'));
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_main',serialize($token));

            //send confirmation mail to user if he used an email as contact
            $email = $user->getEmail();
            if($email){
                $info = array(
                            'subject' => 'Welkom bij Roeselare Vrijwilligt',
                            'template' => 'registration.html.twig',
                            'txt/plain' => 'registration.txt.twig',
                            'to' => $email,
                            'data' => array(
                                'user' => $user,
                            ),
                        );

                $this->sendMail($user, $info);
            }

            //send mail to organisation if the user used an organisation as contact
            $contactOrganisation = $user->getContactOrganisation();
            if($contactOrganisation){
                //TODO: get all admins with a digest status of 1 & send mail + to organisation -> add method to Organisation
                //TODO: add all other admins to cron job for mails table along with info on user -> add method to new supercontroller?
                $info = array(
                            'subject' => 'Een nieuwe vrijwilliger koos u als bemiddelingsorganisatie',
                            'template' => 'newCharge.html.twig',
                            'txt/plain' => 'newCharge.txt.twig',
                            'to' => $contactOrganisation->getEmail(),
                            'data' => array(
                                'user' => $user,
                                'org' => $contactOrganisation,
                            ),
                        );

                $this->sendMail($user, $info);
            }

            //set a success message
            $this->addFlash('approve_message', 'Een nieuwe gebruiker met naam ' . $user->getFirstname() . ' ' . $user->getLastname() . ' werd succesvol aangemaakt' . ($email ? '.  Een bevestigingsbericht werd gestuurd naar ' . $email . '.' : '.')
            );
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            //set an error message
            $this->addFlash('error', 'U vergat een veld of gaf een foutieve waarde in voor één van de velden.  Gelieve het formulier na te kijken en bij het veld waar de foutmelding staat de nodige stappen te ondernemen.'
            );
        }

        return $this->render(
           "person/vrijwilliger_worden.html.twig",
           ["form" => $form->createView()] );
    }

    /**
    * @Route("/login", name = "login", options = { "i18n" = false })
    */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get("security.authentication_utils");
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            "security/login.html.twig",
            array(
                // last username entered by the user
                "last_username" => $lastUsername,
                "error"         => $error,
                "path"          => false,
            )
        );
    }


    /**
     * @Route("/loginAccountKit", name="loginAccountKit")
     */
    public function loginAccountKit(Request $request)
    {
        return $this->render("info/faq.html.twig");
    }


    /**
    * @Route("/status", name="status_testing")
    */
    public function statusAction(){
        $em = $this->getDoctrine()->getManager();
        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $myOrganisations = $em->getRepository("AppBundle:Organisation")->findBy(array('creator' => $userId));

        return $this->render("security/loginstatus.html.twig", array('myOrganisations' => $myOrganisations));
    }
}
