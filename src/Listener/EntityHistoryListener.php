<?php

declare(strict_types=1);

namespace App\Listener;

use App\Entity\HistoryEntityInterface;
use DateTimeImmutable;
use Doctrine\ORM\Event\LifecycleEventArgs;

class EntityHistoryListener
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (!$entity instanceof HistoryEntityInterface) {
            return;
        }

        $date = new DateTimeImmutable();
        $entity->setCreatedAt($date);
        $entity->setUpdatedAt($date);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (!$entity instanceof HistoryEntityInterface) {
            return;
        }

        $entity->setUpdatedAt(new DateTimeImmutable());
    }
}
