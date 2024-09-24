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
     * Récupère une demande avec ses bénéficiaires associés
     *
     * @param int $id L'identifiant de la demande
     * @return Demande|null
     */
    public function findDemandeWithAllRelations(int $id): ?Demande
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.beneficiaires', 'b')
            ->addSelect('b')
            ->leftJoin('b.libelle_prof', 'tp')
            ->addSelect('tp')
            ->leftJoin('d.origine', 'o')
            ->addSelect('o')
            ->leftJoin('d.siteInitial', 'sr')
            ->addSelect('sr')
            ->leftJoin('d.type_demande', 'td')
            ->addSelect('td')
            ->leftJoin('d.position_demande', 'pd')
            ->addSelect('pd')
            ->leftJoin('d.revenus', 'r')
            ->addSelect('r')
            ->leftJoin('r.type_revenu', 'tr')
            ->addSelect('tr')
            ->leftJoin('d.charges', 'c')
            ->addSelect('c')
            ->leftJoin('c.type_charge', 'tc')
            ->addSelect('tc')
            ->leftJoin('d.dettes', 'de')
            ->addSelect('de')
            ->leftJoin('de.type_dette', 'tde')
            ->addSelect('tde')
            ->leftJoin('d.historiqueAvcts', 'ha')
            ->addSelect('ha')
            ->leftJoin('ha.avancement', 'av')
            ->addSelect('av')
            ->leftJoin('d.RendezVous', 'rv')
            ->addSelect('rv')
            ->leftJoin('rv.idSite', 'pesi')
            ->addSelect('pesi')
            ->leftJoin('pesi.idSite', 'sit')
            ->addSelect('sit')
            ->leftJoin('d.users', 'u')
            ->addSelect('u')

            // Trier l'historique des avancements par date décroissante
            ->orderBy('ha.createdAt', 'DESC')
            // Trier l'historique des rendez-vous par date décroissante
            //->addOrderBy('rv.dateAt', 'DESC')

            ->where('d.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
