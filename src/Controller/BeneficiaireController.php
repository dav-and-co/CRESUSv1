<?php


// permet de déclarer des types pour chacune des variables INT ...
declare(strict_types=1);

// on crée un namespace qui permet d'identifier le chemin afin d'utiliser la classe actuelle
namespace App\Controller;

// Importation des classes nécessaires pour ce contrôleur
use App\Entity\Beneficiaire;
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

        // Création du formulaire pour ajouter un nouveau bénéficiaire basé sur BeneficiaireType
        $beneficiaireCreateForm = $this->createForm(BeneficiaireType::class, $beneficiaire);
        $beneficiaireCreateForm->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($beneficiaireCreateForm->isSubmitted() && $beneficiaireCreateForm->isValid()) {

            // persiste et enregistre l'objet en base de données
            $entityManager->persist($beneficiaire);
            $entityManager->flush();

            // Ajoute un message flash de succès
            $this->addFlash('success', 'Bénéficiaire bien ajouté !');
            return $this->redirectToRoute('insertBeneficiaire');
        }
        // Création de la vue du formulaire
        $beneficiaireCreateFormView = $beneficiaireCreateForm->createView();

        // Affiche la vue avec le formulaire de création de bénéficiaire
        return $this->render('interne/page/insertBeneficiaire.html.twig', [
            'beneficiaireForm' => $beneficiaireCreateFormView
        ]);
    }

//--------------------------------------------------------------------------------------------------------------
    #[Route('/benevole/insertDemande/{id}', name: 'insertDemande')]
    public function insertDemande(Request $request, int $id): Response
    {


        return $this->render('interne/page/insertDemande.html.twig', [
            'id'=>$id
        ]);
    }

//--------------------------------------------------------------------------------------------------------------
    #[Route('/benevole/modifDemande/{id}', name: 'modifDemande')]
    public function modifDemande(Request $request, EntityManagerInterface $entityManager, DemandeRepository $DemandeRepository, int $id): Response
    {
        // Récupération du bénévole depuis la base de données en fonction de son ID - donnée poussée dans la route
        $demande = $DemandeRepository->find($id);

        // Création du formulaire de modification lié à l'entité User
        $demandeModifForm = $this->createForm(DemandeType::class, $demande);


        // Traitement de la requête HTTP
        $demandeModifForm->handleRequest($request);

        return $this->render('interne/page/modifDemande.html.twig', [
            'id'=>$id
        ]);
    }

//--------------------------------------------------------------------------------------------------------------



}