<?php

namespace App\DataFixtures;

use DateInterval;
use Faker\Factory;
use App\Entity\Room;

// les différentes entités
use App\Entity\User;
use App\Entity\Status;
use DateTimeImmutable;
use App\Entity\Address;
use App\Entity\Ergonomy;
use App\Entity\Software;
use App\Entity\Equipment;
use App\Entity\ImagesRoom;
use App\Entity\Reservation;
use App\Entity\TypeEquipment;
use App\Entity\EquipmentRoomQuantity;
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
                array_push($ergonomiesObjects, $ergonomyObject);
            $manager->persist($ergonomyObject);
           
        }

        //création des softwares. Ces derniers seront liés aux équipements de type Ordinateur

        $softwareNames = ['Windows', 'Linux', 'MacOS', 'Android', 'IOS', 'Word', 'Excel', 'Powerpoint', 'Outlook', 'Teams', 'Zoom', 'Photoshop', 'Illustrator', 'Indesign', 'Premiere Pro', 'After Effects', 'Final Cut Pro', 'Cubase', 'Ableton Live', 'FL Studio', 'Studio One', 'Reaper', 'Bitwig Studio'];
        $softwareObjects = [];
        //chaque softWare est renseigné dans la base. La version est générée aléatoirement. 
        foreach ($softwareNames as  $softwareName) {
            $softwareObject = new Software();
            $softwareObject->setName($softwareName)
                ->setVersion($faker->randomFloat(1, 1, 10));
                array_push($softwareObjects, $softwareObject);
            $manager->persist($softwareObject);
        }

        //////////////

        //création des status. 

        $statusNames = ['disponible','indisponible', 'Reservé', 'pré-reservé', 'en maintenance'];
        $statusObjects = [];

        foreach ($statusNames as $statusName) {
            $statusObject = new Status();
            $statusObject->setName($statusName);
            array_push($statusObjects, $statusObject);
            $manager->persist($statusObject);
        
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
                array_push($typeEquipmentObjects,$typeEquipmentObject);
                $manager->persist($typeEquipmentObject);
              
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
                                ->setIcon($faker->imageUrl(640,480,"equipement'.$i.'",true))
                    //pour le setType, récupération aléatoire d'un objet selon la taille du tableau $typeEquipmentObjects
                                ->setType($typeEquipmentObjects[$faker->numberBetween(0,count($typeEquipmentObjects)-1)]);
                //si l'information est un ordinateur, on lui ajoute des softwares de façon aléatoire. Pour l'exemple, pas plus de 5 logiciels par ordinateur.
                if($equipmentObject->getType()->getName()==='ordinateur'){
                    for($j=0;$j<$faker->numberBetween(1,5);$j++){
                    $equipmentObject->addSoftware($softwareObjects[$faker->numberBetween(0,count($softwareObjects)-1)]);

                }
            }
            array_push($equipments,$equipmentObject);
            array_push($equipments,$equipmentObject);
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
                $rooms=[];
                $roomObject=[];

                for($i=0;$i<10;$i++){


                    /**
                     * Pour l'équipement, récupération d'un numéro aléatoire entre 1 et le nombre d'équipements.
                     * Ce nombre permettra d'identifié l'objet equipement instancié pour l'ajouter à la salle.
                     *  
                     */

                     $equipNumber = $faker->numberBetween(1,count($equipments));

                    //  Instanciation de l'objet image avec le nom et numéro d'index

                    $imagesRoom=[];
                    for($j=0;$j<rand(1,3);$j++){
                        $imgRoomObject=new ImagesRoom();
                        $imgRoomObject->setPath($faker->imageUrl(640,480,"room'.$i.'",true));
                        array_push($imagesRoom,$imgRoomObject);
                        //on persiste l'image
                        $manager->persist($imgRoomObject);
                        
                    }
                     

                    $roomObject=new Room();
                    $roomObject->setName($faker->word())
                                ->setDescription($faker->text())
                                ->setSlug()
                                ->setCapacityMin($faker->numberBetween(1,10))
                                ->setCapacity($roomObject->getCapacityMin()+$faker->numberBetween(1,70))
                                ->setAddress($addresses[$faker->numberBetween(0,count($addresses)-1)])
                                ->setStatus($statusObjects[$faker->numberBetween(0,count($statusObjects)-1)])
                                ->setPrice($faker->randomFloat(2, 20, 300));
                                for($k=0;$k<count($imagesRoom);$k++){
                                    $roomObject->addImagesRoom($imagesRoom[$k])
                                                ->addErgonomy($ergonomiesObjects[$k]);
                                }
                    
               
                               
                
              
                
                
                                // pour l'ajout de l'équipement, utilisation d'une boucle pour instancier différents objet d'equipement. .
                    $numEquipmentsToAdd=$faker->numberBetween(1,5);

                for ($l = 0; $l < $numEquipmentsToAdd; $l++) {
                    // Pour chaque équipement à ajouter, obtenir un numéro aléatoire entre 1 et le nombre d'équipements. Ainsi,on exploitera le même id pour l'objet équipement.
                    $equipNumber = $faker->numberBetween(0, count($equipments)-1);
            
                    // Créez un nouvel enregistrement EquipmentRoomQuantity pour chaque équipement. La quantité n'est pas vérifiée avec la quantité de l'équipement.Choix pour gain de temps.
                    $equipmentRoomQuantity = new EquipmentRoomQuantity();
                    $equipmentRoomQuantity->setRoom($roomObject);
                    $equipmentRoomQuantity->setEquipment($equipments[$equipNumber]);
                    $equipmentRoomQuantity->setQuantity($faker->numberBetween(1, $equipments[$equipNumber]->getQuantity()));
            

                   // Ajoutez l'enregistrement EquipmentRoomQuantity à la salle
                        $roomObject->addEquipmentRoomQuantity($equipmentRoomQuantity);
                        $manager->persist($equipmentRoomQuantity);
    }
    //finalisation de l'instanciation de la salle

                    $roomObject->addEquipmentRoomQuantity($equipmentRoomQuantity);
                    array_push($rooms,$roomObject);
                    $manager->persist($roomObject);
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
                                array_push($users,$userObject);
                    $manager->persist($userObject);
                    
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
                array_push($users,$userObject);
                $manager->persist($userObject);
               
                }

            ////////////
            
            // Création des réservations

                $reservations=[];
                $resaObject=[];

                for($i=0;$i<20;$i++){
                    //avant d'instancier l'objet, définition de la salle concernée par la réservation.
                    $roomReserved = $rooms[$faker->numberBetween(0, count($rooms) - 1)];

                    $resaObject = new Reservation();
                    $startDate = $faker->dateTimeBetween('-1 month', '+6 month');
                    $resaObject->setDateStart($startDate);
                
                    // Génération  un nombre aléatoire entre 1 et 3 pour la durée de réservation.Cela ne tient pas compte si la date est en week-end ou non.
                    $duration = $faker->numberBetween(1, 3);
                    
                    // Calcul de la date de fin en ajoutant la durée en jours à la date de début. la fonction clone permet de copier l'objet
                    $endDate = clone $startDate;
                    $endDate->add(new DateInterval("P{$duration}D"));
                    
                    $resaObject->setDateEnd($endDate);
                    $resaObject->setUsers($users[$faker->numberBetween(0, count($users) - 1)]);
                    
                    // Pour le calcul du prix total, prise en compte la date de début, la date de fin et le prix de la salle.
                    $roomPrice = $roomReserved->getPrice();
                    $totalPrice = $duration * $roomPrice;
                    
                    $resaObject->setTotalPrice($totalPrice)
                        ->setRoom($roomReserved)
                        ->setIsConfirmed($faker->randomElement([true, false]));
                        array_push($reservations,$resaObject);
                    
                    $manager->persist($resaObject);
                   

                }

        $manager->flush();
    }
}
