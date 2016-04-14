<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PlaceholderController extends Controller
{
    /**
     * @Route("/vrijwilliger", name="vrijwilliger_worden")
     */
    public function vrijwilliger_worden()
    {
        return $this->render("vrijwilliger/worden.html.twig");
    }

    /**
     * @Route("/vereniging", name="vrijwilliger_vinden")
     */
    public function vrijwilligerworden()
    {
        return $this->render("vereniging/vrijwilliger_vinden.html.twig");
    }
}
