<?php

namespace App\EventSubscriber;

use App\Entity\Room;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class SlugRoomSubscriber implements EventSubscriberInterface
{
    public function setSlugOnRoom($event): void
    {
        $entity = $event->getEntityInstance();

        //si l'entité n'est pas la salle, alors on ne fait rien
        if (!($entity instanceof Room)) {
            return;
        }
//la création du slug prend en charge l'UUID automatiquement. EN cas de création d'une salle, on appelle automatiquement cette fonction qui va générer un UUID et le mettre dans le champ slug.

// $slug=Uuid::v4()->__toString();
        $entity->setSlug();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['setSlugOnRoom'],
        ];
    }
}
