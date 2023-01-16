<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Event\OnFlushEventArgs;

class EmailForCreatedUserSender
{
    public function onFlush(OnFlushEventArgs $eventArgs): void
    {
        $ouw = $eventArgs->getObjectManager()->getUnitOfWork();

        foreach ($ouw->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof User) {
                // TODO send email
            }
        }
    }
}
