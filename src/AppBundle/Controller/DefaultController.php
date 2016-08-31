<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
<<<<<<< HEAD
=======
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Person;
use AppBundle\Entity\Form\UserType;
use AppBundle\Entity\Contact;
use AppBundle\Entity\Skillproficiency;
use AppBundle\Entity\Skill;

>>>>>>> 425577f92ba02987d807c89208595ae3766a9613
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Person;
use AppBundle\Entity\Form\PersonType;
use AppBundle\Entity\Contact;
use AppBundle\Entity\Skillproficiency;
use AppBundle\Entity\Skill;
use AppBundle\Entity\Volunteer;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }

    /**
     * @Route("/mode", name="mode_testing")
     */
    public function modeAction()
    {
        $mode = $this->container->get('kernel')->getEnvironment();
        $html = "<html><body><br />".$mode."<br /></body></html>";
        return new Response($html);
    }
}
