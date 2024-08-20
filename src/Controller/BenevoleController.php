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
    #[Route('/admin/benevole/formulaires', 'formulaires')]
    public function readforms(FormulaireRepository $FormulaireRepository)
    {
        $selecteur = 'is_traite';
        $valeur = false;
        $tri = 'createdAt';
        $ordre = 'ASC';

        // récupère tous les articles en BDD non traités et triés par date de création croissant
        $formulaires = $FormulaireRepository->findBy([$selecteur => $valeur], [$tri => $ordre]);


        return $this->render('interne/page/listFormulaires.html.twig', [
            'formulaires' =>  $formulaires
        ]);
    }






}

