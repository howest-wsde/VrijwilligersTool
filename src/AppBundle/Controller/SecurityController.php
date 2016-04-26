<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
<<<<<<< HEAD
use AppBundle\Entity\Person;
=======
<<<<<<< HEAD
use AppBundle\Entity\Person;
=======
use AppBundle\Entity\Volunteer;
>>>>>>> master
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
use AppBundle\Entity\Form\UserType;

class SecurityController extends Controller
{
    /**
    * @Route("/register", name="register_user")
<<<<<<< HEAD
    * @Route("/vrijwilliger", name="vrijwilliger_worden")
=======
<<<<<<< HEAD
    * @Route("/vrijwilliger", name="vrijwilliger_worden")
=======
>>>>>>> master
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
    */
    public function registerAction(Request $request)
    {
        //TODO: http://symfony.com/doc/current/cookbook/doctrine/registration_form.html
<<<<<<< HEAD
        $user = new Person();
=======
<<<<<<< HEAD
        $user = new Person();
=======
        $user = new Volunteer();
>>>>>>> master
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
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

<<<<<<< HEAD
            return $this->redirectToRoute("vacaturesopmaat");
        }

        return $this->render(
           'person/maakprofiel.html.twig',
=======
<<<<<<< HEAD
            return $this->redirectToRoute("vacaturesopmaat");
        }

        return $this->render(
           'person/maakprofiel.html.twig',
=======
            return $this->redirectToRoute("status_testing");
        }

        return $this->render(
           'security/register.html.twig',
>>>>>>> master
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
           array('form' => $form->createView())
       );
    }

    /**
    * @Route("/login", name="login_testing")
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
