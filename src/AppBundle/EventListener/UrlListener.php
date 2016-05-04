<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Vacancy;
use AppBundle\Entity\Organisation;
use AppBundle\UrlEncoder\UrlEncoder;

class UrlListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Vacancy)
        {
            $encoder = new UrlEncoder($args->getEntityManager());
            $entity->setUrlId($encoder->encode($entity, $entity->getTitle()));
        }
        else if ($entity instanceof Organisation)
        {
            $encoder = new UrlEncoder($args->getEntityManager());
            $entity->setUrlId($encoder->encode($entity, $entity->getName()));
        }
    }
}
