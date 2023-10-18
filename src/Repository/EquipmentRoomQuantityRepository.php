<?php

namespace App\Repository;

use App\Entity\EquipmentRoomQuantity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EquipmentRoomQuantity>
 *
 * @method EquipmentRoomQuantity|null find($id, $lockMode = null, $lockVersion = null)
 * @method EquipmentRoomQuantity|null findOneBy(array $criteria, array $orderBy = null)
 * @method EquipmentRoomQuantity[]    findAll()
 * @method EquipmentRoomQuantity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipmentRoomQuantityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EquipmentRoomQuantity::class);
    }

//    /**
//     * @return EquipmentRoomQuantity[] Returns an array of EquipmentRoomQuantity objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EquipmentRoomQuantity
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
