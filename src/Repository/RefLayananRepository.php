<?php

namespace App\Repository;

use App\Entity\RefLayanan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RefLayanan>
 */
class RefLayananRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefLayanan::class);
    }

    public function findGroupPtsp()
    {
        return $this->createQueryBuilder('i')
            ->select('p.id', 'p.nama', 'COUNT(i.id) as total')
            ->join('i.refPtsp', 'p')
            ->groupBy('p.id')
            ->getQuery()
            ->getResult()
            ;
    }


    //    /**
    //     * @return RefLayanan[] Returns an array of RefLayanan objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?RefLayanan
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
