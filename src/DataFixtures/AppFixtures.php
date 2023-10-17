<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Room;
use App\Entity\User;

// les différentes entités
use App\Entity\Status;
use App\Entity\Address;
use App\Entity\Ergonomy;
use App\Entity\Software;
use App\Entity\Equipment;
use App\Entity\ImagesRoom;
use App\Entity\Reservation;
use App\Entity\TypeEquipment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        /**
         * Utilisation de faker pour générer des données aléatoires.
         * https://fakerphp.github.io/formatters/text-and-paragraphs/
         */
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
        //création des softwares. Ces derniers seront liés aux équipements de type Ordinateur

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
         * TODO bien penser à placer l'icone dans le dossier Public/images/typeEquipment 
         */

         $typeEquipments=['ordinateur','tablette','imprimante','scanner','projecteur','tableau','table','chaise','tableau numérique','autre'];
            $typeEquipmentObjects=[];

            foreach($typeEquipments as $typeEquipment){
                $typeEquipmentObject=new TypeEquipment();
                $typeEquipmentObject->setName($typeEquipment)
                                    ->setIcon("/images/typeEquipment/default.png"); //
                $manager->persist($typeEquipmentObject);
                $typeEquipmentObjects[]=$typeEquipmentObject;
            }

        //////////////
        /**
         * Gestion des équipements. Garder en alerte la notion de "type" d'équipement. En effet, s'il s'agit d'un ordinateur, on y associera des softwares.
         */

            $equipmentObject=[];
            $equipments=[];
                
                
                for($i=0;$i<50;$i++){
                $equipmentObject=new Equipment();
                $equipmentObject->setName($faker->word())
                                ->setDescription($faker->text(200))
                                ->setQuantity($faker->numberBetween(1,10))
                    //pour le setType, récupération aléatoire d'un objet selon la taille du tableau $typeEquipmentObjects
                                ->setType($typeEquipmentObjects[$faker->numberBetween(0,count($typeEquipmentObjects)-1)]);
                //si l'information est un ordinateur, on lui ajoute des softwares de façon aléatoire. Pour l'exemple, pas plus de 5 logiciels par ordinateur.
                if($equipmentObject->getType()->getName()==='ordinateur'){
                    for($j=0;$j<$faker->numberBetween(1,5);$j++){
                    $equipmentObject->addSoftware($softwareObjects[$faker->numberBetween(0,count($softwareObjects)-1)]);

                }
            }
                $manager->persist($equipmentObject);
                $equipments[]=$equipmentObject;
            }

            /////////////
            /**
             * Gestion des adresses. Utilisation de l'API BAN du Gouvernement pour le passage final en production.
             */

             $addressObject=[];
                $addresses=[];

             for ($i=0;$i<50;$i++){
                 $addressObject=new Address();
                 $addressObject->setAddress($faker->streetAddress())
                                ->setCity($faker->city())
                                ->setZip($faker->postcode())
                //choix de ne pas dépasser 8 étages pour l'étage. Si 0, cela signifie que l'équipement est au rez-de-chaussée.
                                ->setFloor($faker->numberBetween(0,8));
                $manager->persist($addressObject);
                $addresses[]=$addressObject;
             }

                /////////////

                /**
                 * Gestion des salles.
                 */
                
                 $roomsObjects=[];

                for($i=0;$i<10;$i++){


                    /**
                     * Pour l'équipement, récupération d'un numéro aléatoire entre 1 et le nombre d'équipements.Placé en brut en raison d'un bug.
                     * Ce nombre permettra d'identifié l'objet equipement instancié pour l'ajouter à la salle.
                     *  
                     */

                     $equipNumber = $faker->numberBetween(1,49);

                    //  Instanciation de l'objet image avec le nom et numéro d'index
                     $imgRoomObject=new ImagesRoom();
                        $imgRoomObject->setPath($faker->imageUrl(640,480,"room'.$i.'",true));
                        //on persiste l'image
                        $manager->persist($imgRoomObject);

                    $roomObject=new Room();
                    $roomObject->setName($faker->word())
                                ->setDescription($faker->text())
                                ->setCapacity($faker->numberBetween(1,70))
                                ->setAddress($addresses[$faker->numberBetween(0,count($addresses)-1)])
                                ->setStatus($statusObjects[$faker->numberBetween(0,count($statusObjects)-1)])
                                ->addImagesRoom($imgRoomObject)
                                ->addErgonomy($ergonomiesObjects[$faker->numberBetween(0,count($ergonomiesObjects)-1)])
                // pour l'ajout de l'équipement, on place la quantité attribué. Elle ne doit pas dépasser la quantité de l'équipement. Toutefois, il est normalement nécessaire de faire une requête pour vérifier la quantité disponible. Mais pour l'exemple, on ne le fait pas.
                                ->addEquipment($equipments[$equipNumber],
                                $faker->numberBetween(1,($equipments[$equipNumber]->getQuantity())));
                    $manager->persist($roomObject);
                    $roomsObjects[]=$roomObject;
                }


                /////////////

                /**
                 * gestions des utilisateurs
                 */

                 $users=[];
                 $userObject=[];

                 //création d'un objet user standard
                for($i=0;$i<10;$i++){
                    $userObject=new User();
                    $userObject->setEmail($faker->email())
                                ->setFirstname($faker->firstName())
                                ->setLastname($faker->lastName())
                                ->setPhone($faker->phoneNumber())
                                ->setPassword($faker->password())
                                ->setRoles(['ROLE_USER'])
                                ->setAddress($addresses[$faker->numberBetween(0,count($addresses)-1)]);
                    $manager->persist($userObject);
                    $users[]=$userObject;
                }

                //création d'un objet user admin | 3 au total
                for($i=0;$i<3;$i++){
                $userObject=new User();
                $userObject->setEmail($faker->email())
                            ->setFirstname($faker->firstName())
                            ->setLastname($faker->lastName())
                            ->setPhone($faker->phoneNumber())
                            ->setPassword($faker->password())
                            ->setRoles(['ROLE_ADMIN'])
                            ->setAddress($addresses[$faker->numberBetween(0,count($addresses)-1)]);
                $manager->persist($userObject);
                $users[]=$userObject;
                }


        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
