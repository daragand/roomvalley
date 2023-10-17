<?php

namespace App\Repository;

use App\Entity\TypeEquipment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeEquipment>
 *
 * @method TypeEquipment|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeEquipment|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeEquipment[]    findAll()
 * @method TypeEquipment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeEquipmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeEquipment::class);
    }

//    /**
//     * @return TypeEquipment[] Returns an array of TypeEquipment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TypeEquipment
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
