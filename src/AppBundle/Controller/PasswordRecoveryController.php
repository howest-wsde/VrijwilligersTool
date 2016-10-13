<?php
namespace AppBundle\Controller;


use AppBundle\Entity\Form\ResetPasswordType;
use AppBundle\Entity\PasswordRecover;
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
 * - zelfde paswoord constraints als person toevoegen
 * - vertalingen implementeren
 *
 *
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

            //echo "<script>alert('valid')</script>";
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository("AppBundle:Person")->findBy(array('email'=> $loginCredential));


        }

        return $this->render('passwordrecovery/recover_form.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/paswoord/{hash}", name="password_recover")
     */

    // GETS CALLED BY REQUEST AND INSERTS INTO DB AND SENDS MAIL
    public function recoverAction(Request $request, $hash){


    }

    //HANDLES THE VALIDATION OG HASH URL AND SHOWS FORM PASSWD CHANGE

    public function resetForm(){
        $form = $this->createFormBuilder()
            ->add('newPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'paswoorden komen niet overeen!',
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password')))
            ->add('save', SubmitType::class, array('label' => 'Versturen'))
            ->getForm();
    }




}