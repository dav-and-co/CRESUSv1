<?php

namespace App\Repository;

use App\Entity\TypeDette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeDette>
 */
class TypeDetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeDette::class);
    }
    public function findActiveTypeDettes(): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.isActif = :active')
            ->setParameter('active', true)
            ->orderBy('t.libelle_dette', 'ASC')
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return TypeDette[] Returns an array of TypeDette objects
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

    //    public function findOneBySomeField($value): ?TypeDette
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
