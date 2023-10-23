<?php

namespace App\EventSubscriber;
use App\Entity\Equipment;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SoftwareUpdateSubscriber implements EventSubscriberInterface
{
    public function onBeforeEntityUpdatedEvent($event): void
    {

        /**
 * Cette fonction sert avant tout à retirer tout logiciel renseigné sur un équipement qui n'est pas un ordinateur.
 * le but est de supprimmer les logiciels renseignés si le type n'est pas de type ordinateur. 
 * Elle s'active lorsque le bouton edit est cliqué. Il faut la même chose pour le create.
 */
        $entity = $event->getEntityInstance();

    if (!($entity instanceof Equipment)) {
        return;
    }

    if ($entity->getType()->getName() !== 'ordinateur') {
        foreach ($entity->getSoftware() as $software) {
            $entity->removeSoftware($software);
    }
    }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityUpdatedEvent::class => 'onBeforeEntityUpdatedEvent',
        ];
    }
}
