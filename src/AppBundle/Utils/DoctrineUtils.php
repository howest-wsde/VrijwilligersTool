<?php
namespace AppBundle\Utils;

use Doctrine\ORM\EntityManager;

class DoctrineUtils
{
  /**
   * Get the headcount for a specific entity type
   * @param  EntityManager $em    entity manager for Doctrine
   * @param  string        $class a valid class name managed by Doctrine
   * @return integer              the amount of entities in the table
   */
  function getCount(EntityManager $em, $class){
    return $em->createQuery('select Count(c) from ' . $class . ' c')
       ->getSingleScalarResult();
  }
}
