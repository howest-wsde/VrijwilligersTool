<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Skill;

class SkillController extends controller
{
/**
 * A list of all skill categories
 * @param  integer $nr the maximum amount of skill-categories retrieved
 */
    public function listParentSkillsAction($nr)
    {
        $repository = $this->getDoctrine()
            ->getRepository("AppBundle:Skill");

        $query = $repository->createQueryBuilder("s")
            ->where("s.parent IS NULL")
            ->andWhere("s.name != 'Sector'")
            ->addOrderBy("s.name", "ASC")
            ->getQuery();

        $query->setMaxResults($nr);

        return $this->render("skill/recente_categorien.html.twig",
            ["skills" => $query->getResult()]);
    }
}