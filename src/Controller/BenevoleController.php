<?php


// permet de déclarer des types pour chacune des variables INT ...
declare(strict_types=1);

// on crée un namespace qui permet d'identifier le chemin afin d'utiliser la classe actuelle
namespace App\Controller;

// on appelle le chemin (namespace) des classes utilisées et symfony fera le require de ces classes

use App\Entity\Formulaire;
use App\Form\FormulaireType;
use App\Repository\FormulaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    #[Route('/admin/benevole/formulaires/{isTraite}', 'formulaires', defaults: ['isTraite' => false])]
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
    #[Route('/admin/benevole/formulaire/traiter/{id}', 'traiter_formulaire')]
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



}

