<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Person;
use AppBundle\Entity\Skill;
use AppBundle\Entity\Contact;
use AppBundle\Entity\Form\ContactType;
use AppBundle\Entity\DigestEntry;

class InfopageController extends UtilityController
{
    /**
     * @Route("/tos", name="info_privacy_and_legal")
     */
    public function tos()
    {
        return $this->render("info/privacy_and_legal.html.twig");
    }

    /**
     * @Route("/over_ons", name="info_over_ons")
     */
    public function overons()
    {
        return $this->render("info/over_ons.html.twig");
    }

    /**
     * @Route("/verhalen", name="info_verhalen")
     */
    public function verhalen()
    {
        return $this->render("info/verhalen.html.twig");
    }

    /**
     * @Route("/contact", name="info_contact")
     */
    public function contact(Request $request)
    {
        $contact = (new Contact())->setEmail("benedikt@beuntje.com")->setName("Benedikt Beun");

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $info = array(
                'subject' => "Contactformulier roeselarevrijwilligt.be",
                'template' => 'contact.html.twig',
                'txt/plain' => 'contact.txt.twig',
                'data' => array(
                    'contact' => $contact,
                ),
                'event' => DigestEntry::NEWADMIN,
            );
            $this->sendMail($contact, $info);

            $this->addFlash('approve_message', $this->get('translator')->trans('contact.flash.sent'));


        } else {
            $user = $this->getUser();
          //  $form->setData
        }

        return $this->render("info/contact.html.twig",
            [
                "form" => $form->createView(),
            ]);

    }

    /**
     * @Route("/vrijwilligersinfo/vrijwilligers", name="info_vrijwilligers")
     */
    public function vrijwilligersinfovrijwilligers()
    {
        return $this->render("info/info.vrijwilligers.html.twig");
    }

    /**
     * @Route("/vrijwilligersinfo/organisaties", name="info_organisaties")
     */
    public function vrijwilligersinfoorganisaties()
    {
        return $this->render("info/info.organisaties.html.twig");
    }

    /**
     * @Route("/vrijwilligersinfo", name="info_algemeen")
     */
    public function vrijwilligersinfo()
    {
        return $this->render("info/vrijwilligersinfo.html.twig");
    }
    /**
     * @Route("/wetgeving", name="info_wetgeving")
     */
    public function wetgeving()
    {
        return $this->render("info/wetgeving.html.twig");
    }

    /**
     * @Route("/spelregels", name="info_spelregels")
     */
    public function spelregelsAction()
    {
        return $this->render("info/spelregels.html.twig");
    }

        /**
     * @Route("/profiel-van-een-vrijwilliger", name="info_profiel")
     */
    public function infoProfielAction()
    {
        return $this->render("info/profielvrijwilliger.html.twig");
    }

    /**
     * @Route("/waarom-vrijwilligen", name="info_waarom")
     */
    public function infoWaaromAction()
    {
        return $this->render("info/waaromvrijwilligen.html.twig");
    }

}
