<?php


// permet de déclarer des types pour chacune des variables INT ...
declare(strict_types=1);

// on crée un namespace qui permet d'identifier le chemin afin d'utiliser la classe actuelle
namespace App\Controller;

// on appelle le chemin (namespace) des classes utilisées et symfony fera le require de ces classes

use App\Entity\Formulaire;
use App\Form\FormulaireType;
use App\Entity\Beneficiaire;
use App\Form\BeneficiaireType;
use App\Repository\FormulaireRepository;
use App\Repository\DemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

// on étend la class AbstractController qui permet d'utiliser des fonctions utilitaires pour les controllers (twig etc)
class BenevoleController extends AbstractController
{
//-----------------------------------------------------------------------------------------------------------
    // localhost/CresusV1/public/benevole/Accueil

    // l'url est appelée et éxécute automatiquement la méthode définie sous la route

    // function qui récupère et affiche les types d'accompagnement de l'association
    #[Route('/benevole/Accueil', name: 'benevoleAccueil')]
    public function benevoleaccueil(): response
    {

        return $this->render('interne/page/AccueilBenevole.html.twig');
    }

//-----------------------------------------------------------------------------------------------------------
    // lecture formulaires
    #[Route('/benevole/formulaires/{isTraite}', 'formulaires', defaults: ['isTraite' => false])]
    public function readforms(FormulaireRepository $FormulaireRepository, bool $isTraite = false)
    {
        $selecteur = 'is_traite';
        $valeur = false;
        $tri = 'createdAt';
        $ordre = 'ASC';

        // récupère tous les articles en BDD non traités et triés par date de création croissant
        $formulaires = $FormulaireRepository->findBy(['is_traite' => $isTraite], [$tri => $ordre]);


        return $this->render('interne/page/listFormulaires.html.twig', [
            'formulaires' =>  $formulaires,
            'isTraite' => $isTraite
        ]);
    }


//-----------------------------------------------------------------------------------------------------------
// Modifier le statut 'isTraite'
    #[Route('/benevole/formulaire/traiter/{id}', 'traiter_formulaire')]
    public function traiterFormulaire(FormulaireRepository $formulaireRepository, EntityManagerInterface $entityManager, int $id)
    {
        // Récupère le formulaire par son id
        $formulaire = $formulaireRepository->find($id);

        if ($formulaire) {
            // Inverse le statut 'is_traite'
            $formulaire->setIsTraite(!$formulaire->getIsTraite());

            // Enregistre les modifications dans la base de données
            $entityManager->persist($formulaire);
            $entityManager->flush();
        }

        // Redirige vers la liste des formulaires
        return $this->redirectToRoute('formulaires');
    }

//--------------------------------------------------------------------------------------------------------------

    #[Route('/benevole/rechercheBeneficiaire', name: 'RechercheBeneficiaire')]
    public function rechercheBeneficiaire(Request $request, EntityManagerInterface $entityManager): Response
    {
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

    $form->handleRequest($request);

        // Initialisation des variables de tri avant le traitement du formulaire
        $sort = $request->query->get('sort', 'nom');
        $order = $request->query->get('order', 'asc');

    $beneficiaires = [];

    if ($form->isSubmitted() && $form->isValid()|| $request->query->has('sort')) {
        $data = $form->getData();

        // Construction de la requête pour obtenir les bénéficiaires
        $queryBuilder = $entityManager->getRepository(Beneficiaire::class)->createQueryBuilder('b');

        // Appliquer les filtres en fonction des champs du formulaire
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

        // Appliquer le tri en fonction des paramètres
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

        // Récupérer les résultats
        $beneficiaires = $queryBuilder->getQuery()->getResult();
        // dd($beneficiaires);
    }

    return $this->render('interne/page/RechercheBeneficiaire.html.twig', [
        'form' => $form->createView(),
        'beneficiaires' => $beneficiaires,
        'sortField' => $sort,
        'sortOrder' => $order,
    ]);
    }

//--------------------------------------------------------------------------------------------------------------
    #[Route('/benevole/insertBeneficiaire', name: 'insertBeneficiaire')]
    public function insertBeneficiaire(Request $request, EntityManagerInterface $entityManager): Response
    {
        $beneficiaire = new Beneficiaire();

        $beneficiaireCreateForm = $this->createForm(BeneficiaireType::class, $beneficiaire);
        $beneficiaireCreateForm->handleRequest($request);

        if ($beneficiaireCreateForm->isSubmitted() && $beneficiaireCreateForm->isValid()) {
            $entityManager->persist($beneficiaire);
            $entityManager->flush();

            $this->addFlash('success', 'Bénéficiaire bien ajouté !');
            return $this->redirectToRoute('insertBeneficiaire');
        }
        $beneficiaireCreateFormView = $beneficiaireCreateForm->createView();

        return $this->render('interne/page/insertBeneficiaire.html.twig', [
            'beneficiaireForm' => $beneficiaireCreateFormView
        ]);
    }

//--------------------------------------------------------------------------------------------------------------

    #[Route('/benevole/insertDemande/{id}', name: 'insertDemande')]
    public function insertDemande(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {


        return $this->render('interne/page/insertDemande.html.twig', [
            'id'=>$id
        ]);
    }


//--------------------------------------------------------------------------------------------------------------

    #[Route('/benevole/modifDemande/{id}', name: 'modifDemande')]
    public function modifDemande(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {


        return $this->render('interne/page/modifDemande.html.twig', [
            'id'=>$id
        ]);
    }



}

