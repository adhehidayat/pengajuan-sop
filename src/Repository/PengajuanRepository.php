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
            ->select('count(u.id) + 1 as total')
            ->andWhere('u.contract LIKE :contract')
            ->setParameter('contract', $contract . '%')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findTotal($year)
    {
        return $this->createQueryBuilder('u')
            ->select('count(u.id) as total')
            ->andWhere('SUBSTRING(u.contract, 1, 4) = :year')
            ->setParameter('year', $year)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findTotalContractByTahun()
    {

        $date = new \DateTimeImmutable();
        $start = $date->modify('first day of January');
        $end = $date->modify('last day of December');

        return $this->createQueryBuilder('u')
            ->select(['count(u.id) as total'])
            ->andWhere('u.createAt BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function create($instance, $flush = true): void
    {
        $this->getEntityManager()->persist($instance);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
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
