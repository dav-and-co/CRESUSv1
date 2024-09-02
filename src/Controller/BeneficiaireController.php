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
use App\Entity\TypeProf;
use App\Form\BeneficiaireType;
use App\Form\DemandeType;
use App\Repository\DemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

// La classe BeneficiaireController hérite d'AbstractController, fournissant des méthodes utiles
class BeneficiaireController extends AbstractController
{

//-----------------------------------------------------------------------------------------------------------
    // Cette route est associée à la méthode rechercheBeneficiaire
    #[Route('/benevole/rechercheBeneficiaire', name: 'RechercheBeneficiaire')]
    public function rechercheBeneficiaire(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Création d'un formulaire pour rechercher un bénéficiaire par nom, ou par prénom ou par téléphone
        $form = $this->createFormBuilder()
            ->add('nom', TextType::class, [
                'required' => false,
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Nom'],
            ])
            ->add('prenom', TextType::class, [
                'required' => false,
                'label' => 'Prénom',
                'attr' => ['placeholder' => 'Prénom'],
            ])
            ->add('telephone', TextType::class, [
                'required' => false,
                'label' => 'Téléphone',
                'attr' => ['placeholder' => 'Téléphone'],
            ])
            ->add('rechercher', SubmitType::class, ['label' => 'Rechercher'])
            ->getForm();

        // Traite la requête HTTP si le formulaire est soumis
        $form->handleRequest($request);

        // Initialisation des variables de tri avant le traitement du formulaire
        $sort = $request->query->get('sort', 'nom');
        $order = $request->query->get('order', 'asc');

        // Initialisation du tableau des bénéficiaires
        $beneficiaires = [];

        // Si le formulaire est soumis et valide ou si des paramètres de tri sont passés
        if ($form->isSubmitted() && $form->isValid()|| $request->query->has('sort')) {
            // Récupère les données du formulaire
            $data = $form->getData();

            // Construction de la requête pour obtenir les bénéficiaires
            $queryBuilder = $entityManager->getRepository(Beneficiaire::class)->createQueryBuilder('b');

            // Application des filtres en fonction des champs du formulaire
            if (!empty($data['nom'])) {
                $queryBuilder->andWhere('b.nom_beneficiaire LIKE :nom')
                    ->setParameter('nom', '%' . $data['nom'] . '%');
            }
            if (!empty($data['prenom'])) {
                $queryBuilder->andWhere('b.prenom_beneficiaire LIKE :prenom')
                    ->setParameter('prenom', '%' . $data['prenom'] . '%');
            }
            if (!empty($data['telephone'])) {
                $queryBuilder->andWhere('b.telephone_beneficiaire LIKE :telephone')
                    ->setParameter('telephone', '%' . $data['telephone'] . '%');
            }

            // Application du tri en fonction des paramètres 'sort' et 'order'
            if (in_array($sort, ['nom', 'prenom', 'telephone']) && in_array($order, ['asc', 'desc'])) {
                $sortField = match ($sort) {
                    'nom' => 'b.nom_beneficiaire',
                    'prenom' => 'b.prenom_beneficiaire',
                    'telephone' => 'b.telephone_beneficiaire',
                    default => 'b.nom_beneficiaire',
                };
                $queryBuilder->orderBy($sortField, $order);
            }

            // Jointure pour récupérer les demandes associées
            $queryBuilder->leftJoin('b.demandes', 'd')
                ->addSelect('d');

            // Exécution de la requête et récupération des résultats
            $beneficiaires = $queryBuilder->getQuery()->getResult();
            // dd($beneficiaires);
        }
        // Affiche la vue avec les résultats et le formulaire
        return $this->render('interne/page/RechercheBeneficiaire.html.twig', [
            'form' => $form->createView(),
            'beneficiaires' => $beneficiaires,
            'sortField' => $sort,
            'sortOrder' => $order,
        ]);
    }

//--------------------------------------------------------------------------------------------------------------
// Cette route est associée à la méthode de création du nouveau bénéficiaire
    #[Route('/benevole/insertBeneficiaire', name: 'insertBeneficiaire')]
    public function insertBeneficiaire(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Création d'une nouvelle instance de Beneficiaire (entité)
        $beneficiaire = new Beneficiaire();

        // initialise l'objet TypeProf avec ID 1
        $typeProf = $entityManager->getRepository(TypeProf::class)->find(1);
        if ($typeProf) {
            $beneficiaire->setLibelleProf($typeProf);
        }

        // Création du formulaire pour ajouter un nouveau bénéficiaire basé sur BeneficiaireType
        $beneficiaireCreateForm = $this->createForm(BeneficiaireType::class, $beneficiaire);
        $beneficiaireCreateForm->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($beneficiaireCreateForm->isSubmitted() && $beneficiaireCreateForm->isValid()) {

            // persiste et enregistre l'objet en base de données
            $entityManager->persist($beneficiaire);
            $entityManager->flush();

            // Récupére le bouton cliqué (submit button)
            $clickedButton = $request->get('clicked_button');

            if ($clickedButton === 'create_demande') {
                // Cas 1 : Création d'une demande avec un seul bénéficiaire

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
                $demande->setCreatedAt(new \DateTimeImmutable());

                // Sauvegarde la demande dans la base de données
                $entityManager->persist($demande);
                $entityManager->flush();

                // Redirige vers la vue de la demande
                return $this->redirectToRoute('affichageDemande', ['id' => $demande->getId()]);

            } elseif ($clickedButton === 'add_second_beneficiaire') {
                // Cas 2 : Ajout d un second bénéficiaire

                // Redirige vers le formulaire pour ajouter un deuxième bénéficiaire
                return $this->redirectToRoute('addSecondBeneficiaire', [
                    'firstBeneficiaryId' => $beneficiaire->getId(),
                ]);
            }
        }

        // Création de la vue du formulaire
        $beneficiaireCreateFormView = $beneficiaireCreateForm->createView();

        // Affiche la vue avec le formulaire de création de bénéficiaire
        return $this->render('interne/page/insertBeneficiaire.html.twig', [
            'beneficiaireForm' => $beneficiaireCreateFormView,
        ]);
    }

//--------------------------------------------------------------------------------------------------------------

    #[Route('/benevole/addSecondBeneficiaire/{firstBeneficiaryId}', name: 'addSecondBeneficiaire')]
    public function addSecondBeneficiaire(int $firstBeneficiaryId, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer le premier bénéficiaire
        $firstBeneficiary = $entityManager->getRepository(Beneficiaire::class)->find($firstBeneficiaryId);

        if (!$firstBeneficiary) {
            throw $this->createNotFoundException('Premier bénéficiaire non trouvé');
        }

        // Créer le second bénéficiaire
        $secondBeneficiary = new Beneficiaire();

        // initialise l'objet TypeProf avec ID 1
        $typeProf = $entityManager->getRepository(TypeProf::class)->find(1);
        if ($typeProf) {
            $secondBeneficiary->setLibelleProf($typeProf);
        }

        $beneficiaireCreateForm = $this->createForm(BeneficiaireType::class, $secondBeneficiary);
        $beneficiaireCreateForm->handleRequest($request);

        if ($beneficiaireCreateForm->isSubmitted() && $beneficiaireCreateForm->isValid()) {
            // Sauvegarder le second bénéficiaire
            $entityManager->persist($secondBeneficiary);
            $entityManager->flush();

            // Associe les valeurs obligatoires
            $typeDemande = $entityManager->getRepository(TypeDemande::class)->find(5);
            $positionDemande = $entityManager->getRepository(PositionDemande::class)->find(1);
            $origine = $entityManager->getRepository(Origine::class)->find(1);

            // Créer une demande et y associer les deux bénéficiaires
            $demande = new Demande();
            $demande->addBeneficiaire($firstBeneficiary);
            $demande->addBeneficiaire($secondBeneficiary);
            $demande->setTypeDemande($typeDemande);
            $demande->setOrigine($origine);
            $demande->setPositionDemande($positionDemande);
            $demande->setCreatedAt(new \DateTimeImmutable());

            // Sauvegarder la demande dans la base de données
            $entityManager->persist($demande);
            $entityManager->flush();

            // Rediriger vers la vue de la demande
            return $this->redirectToRoute('affichageDemande', ['id' => $demande->getId()
            ]);
        }

        $beneficiaireCreateFormView = $beneficiaireCreateForm->createView();

        return $this->render('interne/page/addSecondBeneficiaire.html.twig', [
            'firstBeneficiary' => $firstBeneficiary,
            'beneficiaireForm' => $beneficiaireCreateFormView,
        ]);
    }


//--------------------------------------------------------------------------------------------------------------
    #[Route('/benevole/affichageDemande/{id}', name: 'affichageDemande')]
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
    #[Route('/benevole/updateCommentaires/{id}', name: 'MAJcommentaires', methods: ['POST'])]
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
// creation demande pour un bénéficiaire déjà existant - depuis la vue recherche beneficiaire
    #[Route('/benevole/insertDemande/{id}', name: 'insertDemande')]
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

    #[Route('/demande/removeBeneficiaireFromDemande/{demandeId}/{beneficiaireId}', name: 'removeBeneficiaireFromDemande')]
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

}