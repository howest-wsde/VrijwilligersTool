<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        //all users
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:Volunteer')->findAll();

        echo "Volunteers:";
        echo "<br />";
        foreach($users as $user)
        {
            echo "name: ".$user->getFirstname()." ".$user->getLastname()."<br />";
            echo "==="."proficiencies:==="."<br />";
            foreach ($user->getSkillproficiency() as $proficiency) {
                echo "id: ".$proficiency->getId()."<br />";
                echo "type: ".$proficiency->getType()->getName()."<br />";
                echo "proficiency: ".$proficiency->getProficiency()."<br />";
                echo "<br />";
            }
            echo "<br />";
        }
        echo "<br />";

        $html = "<html><body>"."<br /><br />"."</body></html>";
        return new Response($html);
    }
}
