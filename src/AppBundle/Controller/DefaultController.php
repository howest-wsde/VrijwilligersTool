<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Volunteer;
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
     * @Route("/db", name="userskill_testing")
     */
    public function dbTest()
    {
        $em = $this->getDoctrine()->getManager();

        //all users
        $jelle = $em->getRepository('AppBundle:Volunteer')->findOneByFirstname("Jelle");

        echo $jelle."<br />";

        $html = "<html><body>"."<br /><br />"."</body></html>";
        
        return new Response($html);
    }
}
