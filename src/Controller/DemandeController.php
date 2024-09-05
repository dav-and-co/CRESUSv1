<?php


// permet de déclarer des types pour chacune des variables INT ...
declare(strict_types=1);

// on crée un namespace qui permet d'identifier le chemin afin d'utiliser la classe actuelle
namespace App\Controller;

// Importation des classes nécessaires pour ce contrôleur
use App\Entity\Beneficiaire;
use App\Entity\Charge;
use App\Entity\Demande;
use App\Entity\Dette;
use App\Entity\Origine;
use App\Entity\PositionDemande;
use App\Entity\Revenu;
use App\Entity\TypeDemande;
use App\Form\ChargeType;
use App\Form\DetteType;
use App\Form\ModifDemandeType;
use App\Form\RevenuType;
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
        $typeDemande = $entityManager->getRepository(TypeDemande::class)->find(1);
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
    #[Route('/benevole/demande/ajoutRevenu/{id}', name: 'insertRevenu')]
    public function insertRevenu(Request $request, Demande $demande, EntityManagerInterface $entityManager, demandeRepository $demandeRepository,$id ): Response
    {
        $demande = $demandeRepository->find($id);

        // Créer une nouvelle instance de Revenu
        $revenu = new Revenu();
        $revenu->setDemande($demande);

        // Récupérer les bénéficiaires de la demande
        $beneficiaires = $demande->getBeneficiaires()->toArray();;

        // Créer le formulaire, en passant les bénéficiaires dans les options
        $form = $this->createForm(RevenuType::class, $revenu, [
            'beneficiaires' => $beneficiaires,
        ]);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Associer le revenu à la demande (si besoin)
            $revenu->setDemande($demande);

            // Enregistrer le revenu dans la base de données
            $entityManager->persist($revenu);
            $entityManager->flush();

            // Rediriger vers une autre page ou afficher un message de succès
            return $this->redirectToRoute('affichageDemande', ['id' => $demande->getId()]);
        }

        // Afficher le formulaire
        return $this->render('interne/page/insertRevenu.html.twig', [
            'form' => $form->createView(),
            'beneficiaires' => $beneficiaires,
            'demande' => $demande,
        ]);
    }

//--------------------------------------------------------------------------------------------------------------
    #[Route('/benevole/demande/ajoutCharge/{id}', name: 'insertCharge')]
    public function insertCharge(Request $request, Demande $demande, EntityManagerInterface $entityManager, demandeRepository $demandeRepository,$id ): Response
    {
        $demande = $demandeRepository->find($id);

        // Créer une nouvelle instance de Charge
        $charge = new Charge();
        $charge->setDemande($demande);

        // Récupérer les bénéficiaires de la demande
        $beneficiaires = $demande->getBeneficiaires()->toArray();;

        // Créer le formulaire, en passant les bénéficiaires dans les options
        $form = $this->createForm(ChargeType::class, $charge, [
            'beneficiaires' => $beneficiaires,
        ]);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Associer le revenu à la demande (si besoin)
            $charge->setDemande($demande);

            // Enregistrer le revenu dans la base de données
            $entityManager->persist($charge);
            $entityManager->flush();

            // Rediriger vers une autre page ou afficher un message de succès
            return $this->redirectToRoute('affichageDemande', ['id' => $demande->getId()]);
        }

        // Afficher le formulaire
        return $this->render('interne/page/insertCharge.html.twig', [
            'form' => $form->createView(),
            'beneficiaires' => $beneficiaires,
            'demande' => $demande,
        ]);
    }

//--------------------------------------------------------------------------------------------------------------
    #[Route('/benevole/demande/ajoutDette/{id}', name: 'insertDette')]
    public function insertDette(Request $request, Demande $demande, EntityManagerInterface $entityManager, demandeRepository $demandeRepository,$id ): Response
    {
        $demande = $demandeRepository->find($id);

        // Créer une nouvelle instance de Dette
        $dette = new Dette();
        $dette->setDemande($demande);

        // Récupérer les bénéficiaires de la demande
        $beneficiaires = $demande->getBeneficiaires()->toArray();;

        // Créer le formulaire, en passant les bénéficiaires dans les options
        $form = $this->createForm(DetteType::class, $dette, [
            'beneficiaires' => $beneficiaires,
        ]);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Associer le revenu à la demande (si besoin)
            $dette->setDemande($demande);

            // Enregistrer le revenu dans la base de données
            $entityManager->persist($dette);
            $entityManager->flush();

            // Rediriger vers une autre page ou afficher un message de succès
            return $this->redirectToRoute('affichageDemande', ['id' => $demande->getId()]);
        }

        // Afficher le formulaire
        return $this->render('interne/page/insertDette.html.twig', [
            'form' => $form->createView(),
            'beneficiaires' => $beneficiaires,
            'demande' => $demande,
        ]);
    }



//--------------------------------------------------------------------------------------------------------------
    #[Route('/benevole/modificationDemande/{id}', name: 'modif_demande')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'entité Demande par son ID
        $demande = $entityManager->getRepository(Demande::class)->find($id);


        // Créer le formulaire en utilisant ModifDemandeType
        $form = $this->createForm(ModifDemandeType::class, $demande);

        // Gérer la requête (c'est ici que les données POST seront traitées)
        $form->handleRequest($request);

        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Persister les modifications dans la base de données
            $entityManager->flush();

            // Ajouter un message de succès
            $this->addFlash('success', 'La demande a été modifiée avec succès.');

            // Rediriger vers une autre route après le succès de l'édition
            return $this->redirectToRoute('insert_demande', ['id' => $demande->getId()]);
        }

        // Rendre la vue du formulaire
        return $this->render('modifDemande.html.twig', [
            'form' => $form->createView(),
        ]);
    }


//--------------------------------------------------------------------------------------------------------------



}