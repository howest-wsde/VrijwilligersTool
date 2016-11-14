<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ChangePassword;
use AppBundle\Entity\Form\ChangePasswordType;
use AppBundle\Entity\PasswordRecover;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Person;
use AppBundle\Entity\DigestEntry;
use AppBundle\Entity\Form\PersonType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


class SecurityController extends UtilityController
{
    /**
    * @Route("/vrijwilliger_worden", name="register_user")
    */
    public function registerAction(Request $request)
    {
        $t = $this->get('translator');
        //TODO: http://symfony.com/doc/current/cookbook/doctrine/registration_form.html
        $form = $this->createForm(PersonType::class, new Person());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();
            //encode password en replace the plain password to the encoded one
            $password = $this->get("security.password_encoder")
                             ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            //set latitude and longitude
            $this->setCoordinates($user);
            if(empty($user->getUsername())){
                $user->setUsername('');
            }

            //persist the user to the dbase
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            //recreate form to ensure all changes are visible
            $form = $this->createForm(PersonType::class, $user);

            //log in the user as far as Symfony is concerned
            $token = new UsernamePasswordToken($user, $password, 'main', array('ROLE_USER'));
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_main',serialize($token));

            //send confirmation mail to user if he used an email as contact
            $email = $user->getEmail();
            if($email){
                $info = array(
                            'subject' => $t->trans('security.mail.welcome'),
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
                //set digest / send email to all administrators
                $info = array(
                            'subject' => $t->trans('security.mail.newCharge'),
                            'template' => 'newCharge.html.twig',
                            'txt/plain' => 'newCharge.txt.twig',
                            'to' => $contactOrganisation->getEmail(),
                            'data' => array(
                                'user' => $user,
                                'org' => $contactOrganisation,
                            ),
                            'event' => DigestEntry::NEWCHARGE,
                            'newCharge' => $user,
                        );
                $this->digestAndMail($info);
            }

            //set a success message
            $this->addFlash('approve_message', $t->trans('security.flash.newUserStart') . ' ' . $user->getFullName() . ' ' . $t->trans('security.flash.newUser2') . ($email ? $t->trans('security.flash.newUserMail') . $email . '.' : '.')
            );
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            //set an error message
            $this->addFlash('error', $t->trans('general.flash.formError'));
        }

        return $this->render(
           "person/vrijwilliger_worden.html.twig",
           [ "form" => $form->createView() ]);
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


//    /**
//     * @Route("/loginAccountKit", name="loginAccountKit")
//     */
//    public function loginAccountKit(Request $request)
//    {
//        return $this->render("info/faq.html.twig");
//    }


    /**
    * @Route("/status", name="status_testing")
    */
    public function statusAction(){
        $em = $this->getDoctrine()->getManager();
        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $myOrganisations = $em->getRepository("AppBundle:Organisation")->findBy(array('creator' => $userId, "deleted"=>0));

        return $this->render("security/loginstatus.html.twig", array('myOrganisations' => $myOrganisations));
    }


    /**
     * @Route("/veranderwachtwoord", name="change_password")
     */
    public function changePasswordAction(Request $request)
    {
        $form = $this->createForm(ChangePasswordType::class, new ChangePassword());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //controle en validatie voor user password correct wordt door de assert van $oldpassword gedaan in ChangePassword.php
            $newpass = $form->get('newPassword')->getData();
            if($form->get('oldPassword')->getData() != $newpass)
            {
                //encode and set the password
                $user = $this->getUser();
                $password = $this->get("security.password_encoder")
                                 ->encodePassword($user, $newpass);
                $user->setPassword($password);

                //save in DB
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->addFlash('success', 'Uw wachtwoord werd succesvol aangepast!');
            }
            else
            {
                $this->addFlash('error', 'Uw wachtwoord mag niet hetzelfde als uw oude wachtwoord zijn!');
            }
            return $this->render("passwordrecovery/password_recovery_submit_status.html.twig"); //hergebruiken van deze status pagina
        }

        return $this->render('security/change_password.html.twig', array(
            'form' => $form->createView()
        ));
    }









}

