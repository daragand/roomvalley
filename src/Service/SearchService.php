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
        $ergonomy = $request->query->get('ergonomy');
        $capacity = $request->query->get('capacity');
        $equipment = $request->query->get('equipment');

        /**
         * Recherche dans les titres de produits via le QueryBuilder. Le tri s'effectue par le titre, puis la catégories.
         * Les titres sont placés en minuscule ainsi que le critères pour éviter les erreurs de casse.Cela permet de faire une recherche insensible à la casse. Il faut ajouter le lower() pour chaque critère de recherche.          
         * */
        
         $rooms = $this->em->getRepository(Room::class)
         ->createQueryBuilder('room')
         ->join('room.address', 'address')
         ->join('room.status', 'status')
         ->join('room.ergonomy', 'ergonomy')
        ->join('room.equipmentRoomQuantities', 'equipmentRoomQuantities')
        ->join('equipmentRoomQuantities.equipment', 'equipment')
         ->where('lower(room.name) LIKE lower(:search) OR lower(room.description) LIKE lower(:search)')
         ->setParameter('search', '%' . $query . '%')
         ->orderBy('room.name', 'ASC')
         ->getQuery()
         ->getResult();
         ;

         if ($ergonomy) {
            $rooms = array_filter($rooms, function ($room) use ($ergonomy) {
                return $room->getErgonomy()->contains($ergonomy);
            });
        }
         

         return [
             'rooms' => $rooms,
         ];

    }


}