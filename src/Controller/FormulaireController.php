<?php


// permet de déclarer des types pour chacune des variables INT ...
declare(strict_types=1);

// on crée un namespace qui permet d'identifier le chemin afin d'utiliser la classe actuelle
namespace App\Controller;

// Importation des classes nécessaires pour ce contrôleur
use App\Repository\FormulaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// on étend la class AbstractController qui permet d'utiliser des fonctions utilitaires pour les controllers (twig etc)
class FormulaireController extends AbstractController
{
//-----------------------------------------------------------------------------------------------------------
    // localhost/CresusV1/public/benevole/Accueil

    // page provisoire post connexion - à voir comment elle va évoluer

    // function qui récupère et affiche les types d'accompagnement de l'association
    #[Route('/benevole/Accueil', name: 'benevoleAccueil')]
    public function benevoleaccueil(): response
    {
        // affiche la vue Twig
        return $this->render('interne/page/AccueilBenevole.html.twig');
    }

//-----------------------------------------------------------------------------------------------------------
    // Cette méthode gère la lecture des formulaires avec un filtre basé sur le statut 'isTraite'
    #[Route('/benevole/formulaires/{isTraite}', 'formulaires', defaults: ['isTraite' => false])]
    public function readforms(FormulaireRepository $FormulaireRepository, bool $isTraite = false): Response
    {
        // initialisation des variables de filtre et de tri
        $selecteur = 'is_traite';
        $valeur = false;
        $tri = 'createdAt';
        $ordre = 'ASC';

        // récupère tous les articles en BDD non traités et triés par date de création croissant
        $formulaires = $FormulaireRepository->findBy(['is_traite' => $isTraite], [$tri => $ordre]);

        // affiche la vue Twig avec les données nécessaires
        return $this->render('interne/page/listFormulaires.html.twig', [
            'formulaires' =>  $formulaires,
            'isTraite' => $isTraite
        ]);
    }

//-----------------------------------------------------------------------------------------------------------
// Modifier le statut 'isTraite' d'un formulaire spécifique passé par l'id dans le twig
    #[Route('/benevole/formulaire/traiter/{id}', 'traiter_formulaire')]
    public function traiterFormulaire(FormulaireRepository $formulaireRepository, EntityManagerInterface $entityManager, int $id)
    {
        // Récupère le formulaire par son id
        $formulaire = $formulaireRepository->find($id);

        // Vérifie si le formulaire existe
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


}

