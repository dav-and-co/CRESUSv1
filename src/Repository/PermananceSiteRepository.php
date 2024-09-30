<?php

namespace App\Repository;

use App\Entity\PermananceSite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PermananceSite>
 */
class PermananceSiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PermananceSite::class);
    }

    public function findAllWithDetails(): array
    {
        return $this->createQueryBuilder('pesi')
            ->join('pesi.idSite', 'sit')
            ->addSelect('sit')
            ->orderBy('pesi.dateAt', 'DESC')
            ->getQuery()
            ->getResult();
    }



    //    /**
    //     * @return PermananceSite[] Returns an array of PermananceSite objects
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

    //    public function findOneBySomeField($value): ?PermananceSite
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
