<?php

namespace App\Service;

use App\Entity\Room;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class SearchService
{
    private EntityManagerInterface $em;
    private RequestStack $requestStack;


    /**
     * Création de l'objet Search avec l'EntityManagerInterface et le RequestStack. RequestStack permet de récupérer la requête en cours.
     **/
    public function __construct(EntityManagerInterface $em, RequestStack $requestStack)
    {
        $this->em            = $em;
        $this->requestStack  = $requestStack;
    }

    public function search(): array
    {

        $request = $this->requestStack->getCurrentRequest();
        $query   = $request->query->get('q');

/**
 * q représente la recherche sur la pièce. les autres critères de recherche sont récupérés dans la requête au niveau des filtres.
 */
        $ergonomies = $request->request->get('ergonomy');



        $capacity = $request->query->get('capacity');
        $equipments = $request->query->get('equipment');

        /**
         * Recherche dans les titres de produits via le QueryBuilder. Le tri s'effectue par le titre, puis la catégories.
         * Les titres sont placés en minuscule ainsi que le critères pour éviter les erreurs de casse.Cela permet de faire une recherche insensible à la casse. Il faut ajouter le lower() pour chaque critère de recherche.          
         * */
        
         $rooms = $this->em->getRepository(Room::class)
         ->createQueryBuilder('room')
         ->join('room.address', 'address')
         ->join('room.status', 'status')
        ->join('room.equipmentRoomQuantities', 'equipmentRoomQuantities')
        ->join('equipmentRoomQuantities.equipment', 'equipment')
        ->join('room.ergonomy', 'ergonomy')
        ->join('equipment.type', 'typeEquipment')
         ->where('lower(room.name) LIKE lower(:search) OR lower(room.description) LIKE lower(:search) OR lower(typeEquipment.name) LIKE lower(:search) OR lower(ergonomy.name) LIKE lower(:search)')
         ->setParameter('search', '%' . $query . '%')
         ->orderBy('room.name', 'ASC');
         


         //filtrage en fonction des filtres renseignées sous forme conditionnelles. 

         $firstEquipment = true;
//tests pour équipements. 
        //  foreach ($equipments as $equipment) {

        //     //ternaire pour faire un join au départ et un orWhere ensuite.
        //      $joinType = $firstEquipment ? 'join' : 'orWhere';
        //      //liason avec la table equipmentRoomQuantities et les équipements
        //      $rooms->$joinType('equipmentRoomQuantities.equipment', 'equipement')
        //          ->andWhere('equipement.name = :equipment')
        //          ->setParameter('equipment',  $equipment);
        //  //après la première boucle, on passe sur les ORWHERE
        //      $firstEquipment = false;
        //  }

         

         
        // Filtrage en fonction des équipements si des critères ont été sélectionnés
// 
         
$rooms = $rooms->getQuery()->getResult();

         return [
             'rooms' => $rooms,
         ];

    }


}