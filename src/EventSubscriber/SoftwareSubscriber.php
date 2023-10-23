<?php

namespace App\EventSubscriber;

use App\Entity\Equipment;
use App\Entity\Software;
use App\Repository\SoftwareRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SoftwareSubscriber implements EventSubscriberInterface
{
   

/**
 * Cette fonction sert avant tout à retirer tout logiciel renseigné sur un équipement qui n'est pas un ordinateur.
 * le but est de supprimmer les logiciels renseignés si le type n'est pas de type ordinateur. 
 * Elle s'active lorsque le bouton create est cliqué. Il faut la même chose pour le create.
 */
    public function onBeforeEntityPersistedEvent($event): void
    {
        $entity = $event->getEntityInstance();

    if (!($entity instanceof Equipment)) {
        return;
    }
   

/**
 * si le nom du type de l'équipement n'est pas ordinateur, alors on supprime les logiciels renseignés.
 */
    if ($entity->getType()->getName() !== 'ordinateur') {
        foreach ($entity->getSoftware() as $software) {
            $entity->removeSoftware($software);
    }
    }
}
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => 'onBeforeEntityPersistedEvent',
        ];
    }
}
