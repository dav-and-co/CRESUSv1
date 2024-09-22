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
use App\Entity\Site;
use App\Entity\TypeDemande;
use App\Entity\TypeProf;
use App\Form\BeneficiaireType;
use App\Repository\BeneficiaireRepository;
use App\Repository\DemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

// La classe BeneficiaireController hérite d'AbstractController, fournissant des méthodes utiles
class BeneficiaireController extends AbstractController
{

//-----------------------------------------------------------------------------------------------------------
    // permet la recherche d'un bénéficiaire et permettre l'initialisation
    // de la création d'un nouveau bénéficiaire
    // ou modification d'un bénéficiaire
    // ou création d'une nouvelle demande sur un bénéficiaire existant
    // ou afficher une demande spécifique
    #[Route('/benevole/beneficiaire/recherche', name: 'RechercheBeneficiaire')]
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
            'form_submitted' => $form->isSubmitted(),
        ]);
    }

//--------------------------------------------------------------------------------------------------------------
// création d'un nouveau bénéficiaire et s'il n'y a pas de 2e bénéficiaire à créer,
    // création à minima de la demande avec ce bénéficiaire
    #[Route('/benevole/Beneficiaire/insert', name: 'insertBeneficiaire')]
    public function insertBeneficiaire(Request $request, EntityManagerInterface $entityManager, Security $security): Response
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

            // Transformation du numéro de téléphone avant enregistrement
            $telephone = $beneficiaire->getTelephoneBeneficiaire();
            $telephone = $this->formatPhoneNumber($telephone);
            $beneficiaire->setTelephoneBeneficiaire($telephone);

            // persiste et enregistre l'objet en base de données
            $entityManager->persist($beneficiaire);
            $entityManager->flush();

            // Récupére le bouton cliqué (submit button)
            $clickedButton = $request->get('clicked_button');

            if ($clickedButton === 'create_demande') {
                // Cas 1 : Création d'une demande avec un seul bénéficiaire

                // Associe les valeurs obligatoires
                $typeDemande = $entityManager->getRepository(TypeDemande::class)->find(1);
                $positionDemande = $entityManager->getRepository(PositionDemande::class)->find(1);
                $origine = $entityManager->getRepository(Origine::class)->find(1);
                $siteInitial = $entityManager->getRepository(Site::class)->find(2);
                $user = $security->getUser();

                // Crée une nouvelle demande
                $demande = new Demande();
                $demande->addUser($user);
                $demande->addBeneficiaire($beneficiaire);
                $demande->setSiteInitial($siteInitial);
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
    //  suite création 1er beneficiaire, si choix est 2e beneficiciaire, saisie et enregistrement de ce 2e bénéficiaire et
    // création à minima de la demande avec ces 2 bénéficiaires
    #[Route('/benevole/addSecondBeneficiaire/{firstBeneficiaryId}', name: 'addSecondBeneficiaire')]
    public function addSecondBeneficiaire(int $firstBeneficiaryId, Request $request, EntityManagerInterface $entityManager, Security $security): Response
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

            // Transformation du numéro de téléphone avant enregistrement
            $telephone = $secondBeneficiary->getTelephoneBeneficiaire();
            $telephone = $this->formatPhoneNumber($telephone);
            $secondBeneficiary->setTelephoneBeneficiaire($telephone);

            // Sauvegarder le second bénéficiaire
            $entityManager->persist($secondBeneficiary);
            $entityManager->flush();

            // Associe les valeurs obligatoires
            $typeDemande = $entityManager->getRepository(TypeDemande::class)->find(1);
            $positionDemande = $entityManager->getRepository(PositionDemande::class)->find(1);
            $origine = $entityManager->getRepository(Origine::class)->find(1);
            $siteInitial = $entityManager->getRepository(Site::class)->find(2);
            $user = $security->getUser();

            // Créer une demande et y associer les deux bénéficiaires
            $demande = new Demande();
            $demande->addUser($user);
            $demande->setSiteInitial($siteInitial);
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
    // modification d'un bénéficiaire existant
    #[Route('/benevole/beneficiaire/update/{id}', 'updatebeneficiaire')]
    public function updatebeneficiaire(int $id, Request $request, EntityManagerInterface $entityManager, BeneficiaireRepository $BeneficiaireRepository)
    {
        // Récupération du bénévole depuis la base de données en fonction de son ID - donnée poussée dans la route
        $beneficiaire = $BeneficiaireRepository->find($id);

        // Création du formulaire de modification lié à l'entité User
        $beneficiaireModifForm = $this->createForm(BeneficiaireType::class, $beneficiaire);

        // Traitement de la requête HTTP
        $beneficiaireModifForm->handleRequest($request);

        // Vérification si le formulaire a été soumis et est valide
        if ($beneficiaireModifForm->isSubmitted() &&  $beneficiaireModifForm->isValid()) {

            // Transformation du numéro de téléphone avant enregistrement
            $telephone = $beneficiaire->getTelephoneBeneficiaire();
            $telephone = $this->formatPhoneNumber($telephone);
            $beneficiaire->setTelephoneBeneficiaire($telephone);

            // Si la date de naissance est vide (par exemple une chaîne vide), on la met à null
            if (!$beneficiaire->getDdnBeneficiaire()) {
                $beneficiaire->setDdnBeneficiaire(null);
            }

            // Persistance et sauvegarde des modifications en base de données
            $entityManager->persist($beneficiaire);
            $entityManager->flush();

            // Ajout d'un message de succès dans la session
            $this->addFlash('success', 'Demande enregistrée');

            // Vérifie si l'URL de redirection est présente dans la requête
            $redirectUrl = $request->query->get('redirect');
            if ($redirectUrl) {
                return $this->redirect($redirectUrl);
            }

            // Sinon, redirige vers une route par défaut (par exemple la liste des bénéficiaires)
            return $this->redirectToRoute('RechercheBeneficiaire');
        }

        $beneficiaireModifFormView = $beneficiaireModifForm->createView();

        // affiche la vue Twig en y passant données récupérées
        return $this->render('interne/page/modifBeneficiaire.html.twig', [
            'beneficiaireForm' =>$beneficiaireModifFormView,
            'nomBeneficiaire' => $beneficiaire->getNomBeneficiaire(),
        ]);
    }

//--------------------------------------------------------------------------------------------------------------
    // sur demande avec un bénéficiaire, demande d'ajout d'un nouveau bénéficiaire
    // recherche si nouveau bénéficiaire dans base ,
    // si oui, initialisation de l'ajout du bénéficiaire existant sur la demande
    // si non, saisie des données du nouveau bénéficiaire, création du bénéficiaire et ajout à la demande
    #[Route('/benevole/demande/addbeneficiary/{demandeId}', 'addbeneficiarydemande')]
    public function addbeneficiarydemande(Request $request, EntityManagerInterface $entityManager,  int $demandeId): Response
    {
        // Récupération de la demande
        $demande = $entityManager->getRepository(Demande::class)->find($demandeId);

        if (!$demande) {
            throw $this->createNotFoundException('Demande non trouvée.');
        }

        // Création du formulaire simplifié de recherche
        $searchForm = $this->createFormBuilder()
            ->add('nom', TextType::class, ['required' => false])
            ->add('prenom', TextType::class, ['required' => false])
            ->add('telephone', TextType::class, ['required' => false])
            ->add('rechercher', SubmitType::class, ['label' => 'Rechercher'])
            ->getForm();

        $newBeneficiaryForm = $this->createForm(BeneficiaireType::class);

        // Traitement des formulaires
        $searchForm->handleRequest($request);
        $beneficiaires = [];
        $isSearchFormSubmitted = false;

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $data = $searchForm->getData();
            $isSearchFormSubmitted = true;
            $queryBuilder = $entityManager->getRepository(Beneficiaire::class)->createQueryBuilder('b');

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

            $beneficiaires = $queryBuilder->getQuery()->getResult();
        }

        $newBeneficiaryForm->handleRequest($request);

        if ($newBeneficiaryForm->isSubmitted() && $newBeneficiaryForm->isValid()) {
            $beneficiaire = $newBeneficiaryForm->getData();

            // Si le libelle_prof est vide, définir une valeur par défaut
            if (!$beneficiaire->getLibelleProf()) {
                $defaultTypeProf = $entityManager->getRepository(TypeProf::class)->find(1);
                $beneficiaire->setLibelleProf($defaultTypeProf);
            }

            // création du bénéficiaire
            $entityManager->persist($beneficiaire);
            $entityManager->flush();

            // Ajouter le bénéficiaire à la demande
            $demande->addBeneficiaire($beneficiaire);
            $entityManager->flush();

            return $this->redirectToRoute('affichageDemande', ['id' => $demande->getId()]);
        }

        return $this->render('interne/page/secondbenefondemande.html.twig', [
            'searchForm' => $searchForm->createView(),
            'beneficiaires' => $beneficiaires,
            'newBeneficiaryForm' => $newBeneficiaryForm->createView(),
            'demande' => $demande,
            'isSearchFormSubmitted' => $isSearchFormSubmitted,
        ]);
    }

//--------------------------------------------------------------------------------------------------------------

    // action d'ajout d'un bénéficiaire existant sur une demande existante
    #[Route('/benevole/demande/ajoutbenef/{demandeId}/{beneficiaireId}', 'ajouteneficairedemande')]
    public function ajouterBeneficiaire(int $demandeId, int $beneficiaireId, DemandeRepository $demandeRepository, BeneficiaireRepository $beneficiaireRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupérer la demande par son ID
        $demande = $demandeRepository->find($demandeId);
        if (!$demande) {
            throw $this->createNotFoundException('Demande non trouvée');
        }

        // Récupérer le bénéficiaire par son ID
        $beneficiaire = $beneficiaireRepository->find($beneficiaireId);
        if (!$beneficiaire) {
            throw $this->createNotFoundException('Bénéficiaire non trouvé');
        }

        // Vérifier si le bénéficiaire n'est pas déjà associé à la demande
        if (!$demande->getBeneficiaires()->contains($beneficiaire)) {
            // Ajouter le bénéficiaire à la demande
            $demande->addBeneficiaire($beneficiaire);
            $entityManager->persist($demande);
            $entityManager->flush();

            $this->addFlash('success', 'Le bénéficiaire a été ajouté à la demande.');
        } else {
            $this->addFlash('warning', 'Le bénéficiaire est déjà associé à cette demande.');
        }

        // Rediriger vers la page de la demande après l'ajout
        return $this->redirectToRoute('affichageDemande', ['id' => $demandeId]);
    }











//--------------------------------------------------------------------------------------------------------------
    /**
     * Formate un numéro de téléphone selon les règles spécifiées.
     * Remplace +33 par 0, ajoute un 0 au début si nécessaire et supprime les espaces.
     */
    private function formatPhoneNumber(?string $telephone): ?string
    {
        if ($telephone === null) {
            return null;
        }
        // Supprimer les espaces
        $telephone = str_replace(' ', '', $telephone);
        // Remplacer +33 par 0 si le numéro commence par +33
        if (str_starts_with($telephone, '+33')) {
            $telephone = '0' . substr($telephone, 3);
        }
        // Ajouter un 0 au début si nécessaire
        if (!str_starts_with($telephone, '0')) {
            $telephone = '0' . $telephone;
        }
        return $telephone;
    }



}