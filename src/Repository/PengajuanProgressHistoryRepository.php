<?php

namespace App\Repository;

use App\Entity\PengajuanProgressHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PengajuanProgressHistory>
 */
class PengajuanProgressHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PengajuanProgressHistory::class);
    }

    public function findByPengajuan($id)
    {
        return $this->createQueryBuilder('u')
            ->select(['u.id', 'u.status', 'u.createAt', 'u.ket'])
            ->andWhere('u.pengajuan = :pengajuan')
            ->setParameter('pengajuan', $id)
            ->orderBy('u.createAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return PengajuanProgressHistory[] Returns an array of PengajuanProgressHistory objects
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

    //    public function findOneBySomeField($value): ?PengajuanProgressHistory
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
