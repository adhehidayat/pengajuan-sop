<?php

namespace App\Repository;

use App\Entity\PengajuanProgress;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PengajuanProgress>
 */
class PengajuanProgressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PengajuanProgress::class);
    }

    public function subQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('pp')
            ->select('IDENTITY(pp.pengajuan)')
            ->andWhere('pp.createAt = (SELECT MAX(pp2.createAt) FROM App\Entity\PengajuanProgress pp2 WHERE pp2.pengajuan = pp.pengajuan AND pp2.user = :user)')
            ;
    }

    //    /**
    //     * @return PengajuanProgress[] Returns an array of PengajuanProgress objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PengajuanProgress
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
