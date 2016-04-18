<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Entity\{ Organisation, Person, Skill, Testimonial, Vacancy};

class TestController extends Controller
{
    /**
     * @Route("/test/write", name="test_write")
     */
    public function test_writeAction()
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

        $otherperson = new Person();
        $otherperson->setFirstname("koen")
            ->setLastname("cornelis")
            ->setUsername("badlapje")
            ->setPassphrase("niet vielig?")
            ->setStreet("eeklo")
            ->setNumber(8)
            ->setPostalCode(1234)
            ->setCity("Gent")
            ->setEmail("koen.cornelis@student.howest.be")
            ->setTelephone("0123456789");
        $em->persist($otherperson);

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

        $skillparent = new Skill();
        $skillparent->setName("Computers")
            ->setParent(null);
        $em->persist($skillparent);

        $skillchild = new Skill();
        $skillchild->setname("Programming")
            ->setParent($skillparent);
        $em->persist($skillchild);

        $vac = new Vacancy();
        $vac->setTitle("werk gezocht")
            ->setDescription("heel leuk en veel werk!")
            ->setStartdate(new \DateTime())
            ->setEnddate(new \DateTime())
            ->setOrganisation($org)
            ->setSkill($skillparent);
        $em->persist($vac);

        $test = new Testimonial();
        $test->setValue("goed gedaan! flinke jongen")
            ->setSender($otherperson)
            ->setReceiver($person)
            ->setApproved(true);
        $em->persist($test);

        $em->flush();

        return new Response("done");
    }

    /**
     * @Route("/test/read", name="test_read")
     */
    public function test_readAction()
    {
        $em = $this->getDoctrine()->getManager();

        $person = $this->getDoctrine()
            ->getRepository("AppBundle:Person")
            ->findOneById(19);

        $otherperson = $this->getDoctrine()
            ->getRepository("AppBundle:Person")
            ->findOneById(20);

        $org = $this->getDoctrine()
            ->getRepository("AppBundle:Organisation")
            ->findOneById(8);

        $skillparent = $this->getDoctrine()
            ->getRepository("AppBundle:Skill")
            ->findOneById(1);

        $skillchild = $this->getDoctrine()
            ->getRepository("AppBundle:Skill")
            ->findOneById(2);

        $vac = $this->getDoctrine()
            ->getRepository("AppBundle:Vacancy")
            ->findOneById(1);

        //
        $assert_skill = $skillchild->getParent();
        echo "skill:: ".$skillparent."<br />";
        echo "assert: ".$assert_skill."<br />";
        //


        $test = $this->getDoctrine()
            ->getRepository("AppBundle:Testimonial")
            ->findOneById(1);

        return new Response("done");
    }
}
