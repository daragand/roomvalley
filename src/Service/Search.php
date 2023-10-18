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
         * Recherche dans les titres de produits via le QueryBuilder. Le tri s'effectue par le titre, puis la catégories.
         * Les titres sont placés en minuscule ainsi que le critères pour éviter les erreurs de casse.Cela permet de faire une recherche insensible à la casse. Il faut ajouter le lower() pour chaque critère de recherche.          
         * */
        
         $rooms = $this->em->getRepository(Room::class)
         ->createQueryBuilder('room')
         ->where('lower(room.name) LIKE lower(:search) OR lower(room.description) LIKE lower(:search)')
         ->setParameter('search', '%' . $query . '%')
         ->orderBy('room.name', 'ASC')
         ->getQuery()
         ->getResult();
         ;


         /**
         * Retourne un tableau avec les produits trouvés.
         * il est possible d'ajouter les autres critères de recherche dans le tableau dans l'éventualité d'un ajout de recherche sur d'autres critères ci-dessus.
         */

         return [
             'rooms' => $rooms,
         ];

    }


}