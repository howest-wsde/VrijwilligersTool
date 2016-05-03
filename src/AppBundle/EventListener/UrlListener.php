<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Vacancy;
use AppBundle\Entity\Organisation;

class UrlListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // only act on some "Product" entity
        if ($entity instanceof Product or $entity instanceof Organisation)
        {
            $entity->normaliseUrlId();
        }
    }
}
