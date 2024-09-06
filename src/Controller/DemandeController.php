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
use App\Entity\HistoriqueAvct;
use App\Entity\Origine;
use App\Entity\PositionDemande;
use App\Entity\Revenu;
use App\Entity\TypeDemande;
use App\Form\ChargeType;
use App\Form\DemandeType;
use App\Form\DetteType;
use App\Form\HistoriqueAvctType;
use App\Form\ModifDemandeType;
use App\Form\RevenuType;
use App\Repository\ChargeRepository;
use App\Repository\DemandeRepository;
use App\Repository\DetteRepository;
use App\Repository\RevenuRepository;
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
            // Associer le revenu à la demande
            $revenu->setDemande($demande);

            // Enregistrer le revenu dans la base de données
            $entityManager->persist($revenu);
            $entityManager->flush();

            // Si le bouton "Nouvelle saisie" est cliqué
            if ($request->request->has('nouvelle_saisie')) {

                // Ajouter un message flash pour informer l'utilisateur du succès
                $this->addFlash('success', 'Votre saisie a été enregistrée. Vous pouvez maintenant en ajouter une nouvelle.');

                // Rediriger vers la même page pour saisir un nouveau revenu
                return $this->redirectToRoute('insertRevenu', ['id' => $demande->getId()]);
            }

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

            // Si le bouton "Nouvelle saisie" est cliqué
            if ($request->request->has('nouvelle_saisie')) {

                // Ajouter un message flash pour informer l'utilisateur du succès
                $this->addFlash('success', 'Votre saisie a été enregistrée. Vous pouvez maintenant en ajouter une nouvelle.');

                // Rediriger vers la même page pour saisir un nouveau revenu
                return $this->redirectToRoute('insertCharge', ['id' => $demande->getId()]);
            }

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

            // Si le bouton "Nouvelle saisie" est cliqué
            if ($request->request->has('nouvelle_saisie')) {

                // Ajouter un message flash pour informer l'utilisateur du succès
                $this->addFlash('success', 'Votre saisie a été enregistrée. Vous pouvez maintenant en ajouter une nouvelle.');

                // Rediriger vers la même page pour saisir un nouveau revenu
                return $this->redirectToRoute('insertDette', ['id' => $demande->getId()]);
            }


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
    #[Route('/benevole/demande/modifRevenu/{id}', name: 'modif_revenu')]
    public function modifRevenu(Request $request, Revenu $revenu, EntityManagerInterface $entityManager, revenuRepository $revenuRepository, $id): Response
    {
        $revenu = $revenuRepository->find($id);

        // Récupérer la demande liée au revenu
        $demande = $revenu->getDemande();

        // Récupérer les bénéficiaires de la demande
        $beneficiaires = $demande->getBeneficiaires()->toArray();;

        // Créer le formulaire, en passant les bénéficiaires dans les options
        $form = $this->createForm(RevenuType::class, $revenu, [
            'beneficiaires' => $beneficiaires,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Rediriger après la modification
            return $this->redirectToRoute('affichageDemande', ['id' => $demande->getId()]);
        }

        return $this->render('interne/page/insertRevenu.html.twig', [
            'form' => $form->createView(),
            'demande' => $demande,
            'beneficiaires' => $beneficiaires,
            'revenu' => $revenu,
        ]);
    }

//--------------------------------------------------------------------------------------------------------------
    #[Route('/benevole/demande/deleteRevenu/{id}', name: 'delete_revenu')]
    public function deleteRevenu(Revenu $revenu, EntityManagerInterface $entityManager): Response
    {
        $demandeId = $revenu->getDemande()->getId();

        $entityManager->remove($revenu);
        $entityManager->flush();

        return $this->redirectToRoute('affichageDemande', ['id' => $demandeId]);
    }

//--------------------------------------------------------------------------------------------------------------
    #[Route('/benevole/demande/modifcharge/{id}', name: 'modif_charge')]
    public function modifCharge(Request $request, Charge $charge, EntityManagerInterface $entityManager, chargeRepository $chargeRepository, $id): Response
    {
        $charge = $chargeRepository->find($id);

        // Récupérer la demande liée au revenu
        $demande = $charge->getDemande();

        // Récupérer les bénéficiaires de la demande
        $beneficiaires = $demande->getBeneficiaires()->toArray();;

        // Créer le formulaire, en passant les bénéficiaires dans les options
        $form = $this->createForm(ChargeType::class, $charge, [
            'beneficiaires' => $beneficiaires,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Rediriger après la modification
            return $this->redirectToRoute('affichageDemande', ['id' => $demande->getId()]);
        }

        return $this->render('interne/page/insertCharge.html.twig', [
            'form' => $form->createView(),
            'demande' => $demande,
            'beneficiaires' => $beneficiaires,
            'charge' => $charge,
        ]);
    }

//--------------------------------------------------------------------------------------------------------------
    #[Route('/benevole/demande/deleteCharge/{id}', name: 'delete_charge')]
    public function deleteCharge(charge $charge, EntityManagerInterface $entityManager): Response
    {
        $demandeId = $charge->getDemande()->getId();

        $entityManager->remove($charge);
        $entityManager->flush();

        return $this->redirectToRoute('affichageDemande', ['id' => $demandeId]);
    }


//--------------------------------------------------------------------------------------------------------------
    #[Route('/benevole/demande/modifdette/{id}', name: 'modif_dette')]
    public function modifDette(Request $request, Dette $dette, EntityManagerInterface $entityManager, detteRepository $detteRepository, $id): Response
    {
        $dette = $detteRepository->find($id);

        // Récupérer la demande liée au revenu
        $demande = $dette->getDemande();

        // Récupérer les bénéficiaires de la demande
        $beneficiaires = $demande->getBeneficiaires()->toArray();;

        // Créer le formulaire, en passant les bénéficiaires dans les options
        $form = $this->createForm(DetteType::class, $dette, [
            'beneficiaires' => $beneficiaires,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Rediriger après la modification
            return $this->redirectToRoute('affichageDemande', ['id' => $demande->getId()]);
        }

        return $this->render('interne/page/insertDette.html.twig', [
            'form' => $form->createView(),
            'demande' => $demande,
            'beneficiaires' => $beneficiaires,
            'dette' => $dette,
        ]);
    }

//--------------------------------------------------------------------------------------------------------------
    #[Route('/benevole/demande/deletedette/{id}', name: 'delete_dette')]
    public function deleteDette(dette $dette, EntityManagerInterface $entityManager): Response
    {
        $demandeId = $dette->getDemande()->getId();

        $entityManager->remove($dette);
        $entityManager->flush();

        return $this->redirectToRoute('affichageDemande', ['id' => $demandeId]);
    }

//--------------------------------------------------------------------------------------------------------------
    #[Route('/benevole/demande/insert-evoldoss/{id}', name: 'insert_evoldoss')]
    public function insertEvoldoss(Demande $demande, Request $request, EntityManagerInterface $entityManager): Response
    {
        $historiqueAvct = new HistoriqueAvct();
        $form = $this->createForm(HistoriqueAvctType::class, $historiqueAvct, [
            'type_demande' => $demande->getTypeDemande(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $historiqueAvct->setDemande($demande);
            $historiqueAvct->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($historiqueAvct);
            $entityManager->flush();

            $this->addFlash('success', 'L\'avancement a été ajouté.');

            return $this->redirectToRoute('affichageDemande', ['id' => $demande->getId()]);
        }

        return $this->render('interne/page/insertEvoldoss.html.twig', [
            'form' => $form->createView(),
            'demande' => $demande,
        ]);
    }

//--------------------------------------------------------------------------------------------------------------
    #[Route('/benevole/modificationDemande/{id}', name: 'modif_demande')]

        public function modifDemande(Demande $demande, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Créer le formulaire basé sur l'entité Demande
        $form = $this->createForm(ModifDemandeType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Met à jour les données de la demande si bouton save
                $entityManager->flush();

            // Redirige vers la vue
            return $this->redirectToRoute('affichageDemande', ['id' => $demande->getId()]);
        }

        return $this->render('interne/page/modifDemande.html.twig', [
            'form' => $form->createView(),
            'demande' => $demande,
        ]);
    }


//--------------------------------------------------------------------------------------------------------------



}