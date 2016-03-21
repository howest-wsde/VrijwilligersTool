<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Entity\Volunteer;
use AppBundle\Entity\Form\UserType;

use AppBundle\Entity\Contact;
use AppBundle\Entity\Skillproficiency;
use AppBundle\Entity\Skill;

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

    /**
     * @Route("/test", name="twig_testing")
     */
    public function testTwigAction()
    {
        return $this->render('test/index.html.twig', array(
            'name' => "Jelle",
            'base_dir' => "lel"
        ));
    }
    
    /**
     * @Route("/db", name="userskill_testing")
     */
    public function dbAction()
    {
        $em = $this->getDoctrine()->getManager();

        //all users
        $jelle = $em->getRepository('AppBundle:Volunteer')
        ->findOneByFirstname("Jelle");
        echo $jelle."<br />";

        $volunteer = new volunteer();
        $volunteer->setFirstname("tester");
        $volunteer->setLastname("testest");
        $volunteer->setUsername($volunteer->getFirstname()." ".$volunteer->getLastname());

        $em = $this->getDoctrine()->getManager();
        
        $em->persist($volunteer);
        $em->flush();

        $message = "user added to database!";
        $message .= "<br /> (not really though...)";
        $html = "<html><body><br />".$message."<br /></body></html>";
        return new Response($html);
    }



}
