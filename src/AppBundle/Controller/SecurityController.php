<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Person;
use AppBundle\Entity\Form\UserType;

class SecurityController extends Controller
{
    /**
    * @Route("/vrijwilliger_worden", name="register_user")
    */
    public function registerAction(Request $request)
    {
        //TODO: http://symfony.com/doc/current/cookbook/doctrine/registration_form.html
        $user = new Person();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $password = $this->get('security.password_encoder')
                             ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("vacaturesopmaat");
        }
        return $this->render(
           'person/maakprofiel.html.twig',
           array('form' => $form->createView())
       );
    }
    /**
    * @Route("/login", name = "login", options = { "i18n" = false })
    */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render(
            'security/login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }
    /**
    * @Route("/status", name="status_testing")
    */
    public function statusAction(){
        return $this->render('security/loginstatus.html.twig');
    }
}
