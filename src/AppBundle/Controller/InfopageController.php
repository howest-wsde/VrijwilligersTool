<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Person;
use AppBundle\Entity\Skill;

class InfopageController extends Controller
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
     * @Route("/contact", name="info_contact")
     */
    public function contact()
    {
        return $this->render("info/contact.html.twig");
    }

    /**
     * @Route("/vrijwilligersinfo", name="info_vrijwilligersinfo")
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
