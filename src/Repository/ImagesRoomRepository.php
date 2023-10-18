<?php

namespace App\Repository;

use App\Entity\ImagesRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImagesRoom>
 *
 * @method ImagesRoom|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImagesRoom|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImagesRoom[]    findAll()
 * @method ImagesRoom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImagesRoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImagesRoom::class);
    }

//    /**
//     * @return ImagesRoom[] Returns an array of ImagesRoom objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ImagesRoom
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
