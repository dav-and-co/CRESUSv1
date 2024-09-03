<?php


// permet de déclarer des types pour chacune des variables INT ...
declare(strict_types=1);

// on crée un namespace qui permet d'identifier le chemin afin d'utiliser la classe actuelle
namespace App\Controller;

// Importation des classes nécessaires pour ce contrôleur
use App\Entity\Beneficiaire;
use App\Entity\Demande;
use App\Entity\Origine;
use App\Entity\PositionDemande;
use App\Entity\TypeDemande;
use App\Repository\DemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


// La classe DemandeController hérite d'AbstractController, fournissant des méthodes utiles
class DemandeController extends AbstractController
{

//--------------------------------------------------------------------------------------------------------------
    #[Route('/benevole/demande/affichage/{id}', name: 'affichageDemande')]
    public function affichDemande(Request $request, EntityManagerInterface $entityManager, DemandeRepository $DemandeRepository, int $id): Response
    {
        $demande = $DemandeRepository->findDemandeWithAllRelations($id);

        if (!$demande) {
            throw $this->createNotFoundException('La demande n\'existe pas.');
        }
        return $this->render('interne/page/oneDemande.html.twig', [
            'demande' => $demande,
        ]);
    }

//--------------------------------------------------------------------------------------------------------------
// creation demande pour un bénéficiaire déjà existant - depuis la vue recherche beneficiaire
    #[Route('/benevole/demande/insert/{id}', name: 'insertDemande')]
    public function insertDemande(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        // Récupére le bénéficiaire par son ID
        $beneficiaire = $entityManager->getRepository(Beneficiaire::class)->find($id);

        if (!$beneficiaire) {
            throw $this->createNotFoundException('Le bénéficiaire n\'existe pas.');
        }

        // Associe les valeurs obligatoires
        $typeDemande = $entityManager->getRepository(TypeDemande::class)->find(5);
        $positionDemande = $entityManager->getRepository(PositionDemande::class)->find(1);
        $origine = $entityManager->getRepository(Origine::class)->find(1);

        // Crée une nouvelle demande
        $demande = new Demande();
        $demande->addBeneficiaire($beneficiaire);
        $demande->setTypeDemande($typeDemande);
        $demande->setOrigine($origine);
        $demande->setPositionDemande($positionDemande);
        // $demande->setTypeDemande($entityManager->getReference(TypeDemande::class, 5));
        $demande->setCreatedAt(new \DateTimeImmutable());

        // Persiste la demande
        $entityManager->persist($demande);
        $entityManager->flush();

        // Redirige vers l'affichage de la demande
        return $this->redirectToRoute('affichageDemande', ['id' => $demande->getId()
        ]);
    }

//--------------------------------------------------------------------------------------------------------------

    #[Route('/benevole/demande/removeBeneficiaireFromDemande/{demandeId}/{beneficiaireId}', name: 'removeBeneficiaireFromDemande')]
    public function removeBeneficiaireFromDemande(int $demandeId, int $beneficiaireId, EntityManagerInterface $entityManager): Response
    {
        // Récupérer la demande
        $demande = $entityManager->getRepository(Demande::class)->find($demandeId);

        // Récupérer le bénéficiaire
        $beneficiaire = $entityManager->getRepository(Beneficiaire::class)->find($beneficiaireId);

        if (!$demande || !$beneficiaire) {
            throw $this->createNotFoundException('La demande ou le bénéficiaire n\'existe pas.');
        }

        // Supprimer le bénéficiaire de la demande
        $demande->removeBeneficiaire($beneficiaire);

        // Persister les changements
        $entityManager->persist($demande);
        $entityManager->flush();

        // Rediriger vers l'affichage de la demande
        return $this->redirectToRoute('affichageDemande', ['id' => $demandeId]);
    }

//--------------------------------------------------------------------------------------------------------------
    #[Route('/benevole/demande/updateCommentaires/{id}', name: 'MAJcommentaires', methods: ['POST'])]
    public function updateCommentaires(Request $request, EntityManagerInterface $entityManager, DemandeRepository $demandeRepository, int $id): Response
    {
        $demande = $demandeRepository->find($id);

        if (!$demande) {
            throw $this->createNotFoundException('La demande n\'existe pas.');
        }

        $commentaires = $request->request->get('commentaires');
        $demande->setCommentaires($commentaires);

        $entityManager->persist($demande);
        $entityManager->flush();

        // Rediriger vers la page de la demande après la mise à jour
        return $this->redirectToRoute('affichageDemande', [
            'id' => $id
        ]);
    }

//--------------------------------------------------------------------------------------------------------------


}