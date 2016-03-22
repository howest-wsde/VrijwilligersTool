<?php

namespace AppBundle\Entity\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Volunteer;

class VolunteerListener
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // only on Volunteer
        if (!$entity instanceof Volunteer) {
            return;
        }
        if (!$entity->getUsername() == "jelle") {
            return;
        }

        $em = $args->getEntityManager();

        $uow = $em->getUnitOfWork();
        $uow->computeChangeSets();
        $changeset = $uow->getEntityChangeSet($entity);
        $entity->setFirstname("Listener says hi");

        // $em->persist($entity);
        // $em->flush();
    }
}
