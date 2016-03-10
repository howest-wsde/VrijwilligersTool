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
use AppBundle\Entity\Contact;
use AppBundle\Entity\Skillproficiency;
use AppBundle\Entity\Skill;
use AppBundle\Entity\Task;

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
     * @Route("/dbs", name="userskill_testing")
     */
    public function dbAction()
    {
        $em = $this->getDoctrine()->getManager();

        //all users
        $jelle = $em->getRepository('AppBundle:Volunteer')->findOneByFirstname("Jelle");

        echo $jelle."<br />";

        $jelle->setLastname("CrielCriel");
        $em->flush();

        $html = "<html><body>"."<br /><br />"."</body></html>";
        return new Response($html);
    }

<<<<<<< HEAD
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
     * @Route("/new", name="forms_testing")
     */
    public function newAction(Request $request)
    {
        // create a task and give it some dummy data for this example
        $task = new Task();

        $form = $this->createFormBuilder($task)
            ->add('task', TextType::class)
            ->add('dueDate', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Task'))
            ->getForm();

        $form->handleRequest($request);

        return $this->render('default/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
    * @Route("/admin", name="admin_testing")
    */
    public function adminAction()
    {
       return new Response('<html><body>Admin page!</body></html>');
    }

=======

    /**
     * @Route("/layout/{name}")
     */
    public function layoutTest($name)
    { 
        return $this->render("vrijwilliger/show.html.twig",  ["name" => $name]); 
    }
>>>>>>> origin/dev
}
