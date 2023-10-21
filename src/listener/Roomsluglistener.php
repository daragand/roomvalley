<?php

namespace App\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Entity\Room;
use Symfony\Component\Uid\Uuid;

class RoomUuidSlugListener
{
    public function prePersist(Room $room, LifecycleEventArgs $event)
    {
        // Générer le slug UUID uniquement si le champ est vide
        if (empty($room->getSlug())) {
            $uuid = Uuid::v4();
            $room->setSlug($uuid->toRfc4122());
        }
    }
}
