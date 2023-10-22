<?php

namespace App\EventSubscriber;

use App\Entity\Equipment;
use App\Entity\Software;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SoftwareSubscriber implements EventSubscriberInterface
{
    public function onBeforeEntityPersistedEvent($event): void
    {
        $entity = $event->getEntityInstance();

    if (!($entity instanceof Equipment)) {
        return;
    }
    


    if ($entity->getType()->getName() !== 'ordinateur') {
        $entity->addSoftware(null);
    }
    }
    public static function getSubscribedEvents(): array
    {
        return [
            'BeforeEntityPersistedEvent' => 'onBeforeEntityPersistedEvent',
        ];
    }
}
