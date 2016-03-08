<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
            echo $user;
            echo "<br /> - proficiencies - <br />";
            foreach ($user->getSkillproficiencies() as $proficiency) {
                echo $proficiency;
                echo "<br />";
            }
            echo "<br />";
        }
        echo "<br />";

        //test add volunteer
        $volunteer = new Volunteer();
        $volunteer->setFirstname("test");
        $volunteer->setLastname("test");
        $em = $this->getDoctrine()->getManager();

        $html = "<html><body>"."<br /><br />"."</body></html>";
        return new Response($html);
    }
}
