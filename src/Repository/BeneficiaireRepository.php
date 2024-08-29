<?php

namespace App\Repository;

use App\Entity\Beneficiaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Beneficiaire>
 */
class BeneficiaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Beneficiaire::class);
    }


    /**
     * Récupère les libellés prof d'un bénéficiaire donné.
     */
    public function findAllWithTypeProf()
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.libelle_prof', 'tp')
            ->addSelect('tp')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère toutes les demandes associées à un bénéficiaire donné.
     */
    public function findDemandesByBeneficiaire(Beneficiaire $beneficiaire)
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.demandes', 'd')
            ->addSelect('d')
            ->where('b.id = :beneficiaireId')
            ->setParameter('beneficiaireId', $beneficiaire->getId())
            ->getQuery()
            ->getResult();
    }

    public function findBySearchCriteria($criteria, $sortField = 'nom', $sortOrder = 'asc')
    {
        $qb = $this->createQueryBuilder('b');

        if ($criteria['nom']) {
            $qb->andWhere('b.nomBeneficiaire LIKE :nom')
                ->setParameter('nom', '%'.$criteria['nom'].'%');
        }
        if ($criteria['prenom']) {
            $qb->andWhere('b.prenomBeneficiaire LIKE :prenom')
                ->setParameter('prenom', '%'.$criteria['prenom'].'%');
        }
        if ($criteria['telephone']) {
            $qb->andWhere('b.telephoneBeneficiaire LIKE :telephone')
                ->setParameter('telephone', '%'.$criteria['telephone'].'%');
        }

        $qb->orderBy('b.' . $sortField, $sortOrder);

        return $qb->getQuery()->getResult();
    }



    //    /**
    //     * @return Beneficiaire[] Returns an array of Beneficiaire objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Beneficiaire
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
