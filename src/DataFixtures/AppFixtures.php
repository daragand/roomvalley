<?php

namespace App\DataFixtures;

use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

// les différentes entités
use App\Entity\Address;
use App\Entity\Equipment;
use App\Entity\Room;
use App\Entity\Ergonomy;
use App\Entity\ImagesRoom;
use App\Entity\Reservation;
use App\Entity\Software;
use App\Entity\Status;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create($fakerLocale = 'fr_FR');

        /**
         * Création des ergonomies. 
         * la description est générée aléatoirement. L'icone est par défaut.
         * TODO Bien penser à placer l'icone dans le dossier Public/images/ergonomy
         */
        $ergonomies = ['ascenseur', 'lumière naturelle', 'climatisation', 'lumière atificielle', 'wifi', 'toilettes pour PMR', 'parking'];
        $ergonomiesObjects = [];

        foreach ($ergonomies as $ergonomy) {
            $ergonomyObject = new Ergonomy();
            $ergonomyObject->setName($ergonomy)
                ->setDescription($faker->text(200))
                ->setIcon("/images/ergonomy/default.png");
            $manager->persist($ergonomyObject);
            $ergonomiesObjects[] = $ergonomyObject;
        }

        //////////////
        //création des softwares. 

        $softwareNames = ['Windows', 'Linux', 'MacOS', 'Android', 'IOS', 'Word', 'Excel', 'Powerpoint', 'Outlook', 'Teams', 'Zoom', 'Photoshop', 'Illustrator', 'Indesign', 'Premiere Pro', 'After Effects', 'Final Cut Pro', 'Cubase', 'Ableton Live', 'FL Studio', 'Studio One', 'Reaper', 'Bitwig Studio'];
        $softwareObjects = [];
        //chaque softWare est renseigné dans la base. La version est générée aléatoirement. 
        foreach ($softwareNames as  $softwareName) {
            $softwareObject = new Software();
            $softwareObject->setName($softwareName)
                ->setVersion($faker->randomFloat(1, 1, 10));
            $manager->persist($softwareObject);
            $softwareObjects[] = $softwareObject;
        }

        //////////////

        //création des status. 

        $statusNames = ['disponible','indisponible', 'Reservé', 'pré-reservé', 'en maintenance'];
        $statusObjects = [];

        foreach ($statusNames as $statusName) {
            $statusObject = new Status();
            $statusObject->setName($statusName);
            $manager->persist($statusObject);
            $statusObjects[] = $statusObject;
        }

        //////////////

        /**
         * Gestion du type d'équipement.
         * 
         */

         $typeEquipments=['ordinateur','tablette','imprimante','scanner','projecteur','tableau','table','chaise','tableau numérique','autre'];



        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
