<?php

namespace App\Repository;

use App\Entity\Pengajuan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pengajuan>
 */
class PengajuanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pengajuan::class);
    }

    public function findTotalContract($contract)
    {
        return $this->createQueryBuilder('u')
            ->select('count(u.id) as total')
            ->andWhere('u.contract LIKE :contract')
            ->setParameter('contract', $contract . '%')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    //    /**
    //     * @return Pengajuan[] Returns an array of Pengajuan objects
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

    //    public function findOneBySomeField($value): ?Pengajuan
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
