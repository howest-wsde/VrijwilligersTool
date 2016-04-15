<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Entity\Person;
use AppBundle\Entity\Organisation;

class TestController extends Controller
{
    /**
     * @Route("/test", name="test")
     */
    public function TestAction()
    {
        $em = $this->getDoctrine()->getManager();

        $person = new Person();
        $person->setFirstname("jelle")
            ->setLastname("Criel")
            ->setUsername("snellejelle")
            ->setPassphrase("veilig?")
            ->setStreet("Sint-Corneliusstraat")
            ->setNumber(7)
            ->setPostalCode(9280)
            ->setCity("Lebbeke")
            ->setEmail("jelle.criel@student.howest.be")
            ->setTelephone("0477459599");
        $em->persist($person);

        $org = new Organisation();
        $org->setName("Howest")
            ->setEmail("howest@howest.be")
            ->setStreet("Sint-Corneliusstraat")
            ->setNumber(7)
            ->setPostalCode(9280)
            ->setCity("Lebbeke")
            ->setTelephone("0123456789")
            ->setDescription("beter dan vives")
            ->setCreator($person);

        $em->persist($org);

        $em->flush();

        return new Response("done");
    }
}
