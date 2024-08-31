<?php

namespace App\Repository;

use App\Entity\Demande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Demande>
 */
class DemandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demande::class);
    }


    /**
     * Récupère l'origine associée à une demande donnée.

    public function findAllWithOrigine()
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.libelle_origine', 'lo')
            ->addSelect('lo')
            ->getQuery()
            ->getResult();
    }


     * Récupère la position associée à une demande donnée.

    public function findAllWithTypePosition()
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.libelle_position', 'lpo')
            ->addSelect('lpo')
            ->getQuery()
            ->getResult();
    }



     * Récupère tous les bénéficiaires associés à une demande donnée.

    public function findBeneficiairesByDemande(Demande $demande)
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.beneficiaires', 'b')
            ->addSelect('b')
            ->where('d.id = :demandeId')
            ->setParameter('demandeId', $demande->getId())
            ->getQuery()
            ->getResult();
    }


     * Récupère toutes les demandes avec les jointures nécessaires pour TypeDemande et PositionDemande.

    public function findAllWithDetails()
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.type_demande', 'td')
            ->addSelect('td')
            ->leftJoin('d.position_demande', 'pd')
            ->addSelect('pd')
            ->getQuery()
            ->getResult();
    }
*/

    public function findDemandeWithRelations(int $id)
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.type_demande', 'td')
            ->addSelect('td')
            ->leftJoin('d.position_demande', 'pd')
            ->addSelect('pd')
            ->leftJoin('d.origine', 'o')
            ->addSelect('o')
            ->leftJoin('d.revenus', 'r')
            ->addSelect('r')
            ->leftJoin('d.charges', 'c')
            ->addSelect('c')
            ->leftJoin('d.dettes', 'de')
            ->addSelect('de')
            ->leftJoin('d.beneficiaires', 'b')
            ->addSelect('b')
            ->leftJoin('d.historiqueAvcts', 'ha')
            ->addSelect('ha')
            ->leftJoin('d.users', 'u')
            ->addSelect('u')
            ->leftJoin('d.RendezVous', 'rv')
            ->addSelect('rv')
            ->where('d.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }


    //    /**
    //     * @return Demande[] Returns an array of Demande objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Demande
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

}
