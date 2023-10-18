<?php

namespace App\Repository;

use App\Entity\Ergonomy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ergonomy>
 *
 * @method Ergonomy|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ergonomy|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ergonomy[]    findAll()
 * @method Ergonomy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ErgonomyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ergonomy::class);
    }

//    /**
//     * @return Ergonomy[] Returns an array of Ergonomy objects
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

//    public function findOneBySomeField($value): ?Ergonomy
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
