<?php


// permet de déclarer des types pour chacune des variables INT ...
declare(strict_types=1);

// on crée un namespace qui permet d'identifier le chemin afin d'utiliser la classe actuelle
namespace App\Controller\gdPublic;

// on appelle le chemin (namespace) des classes utilisées et symfony fera le require de ces classes

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\TypeDemandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// on étend la class AbstractController qui permet d'utiliser des fonctions utilitaires pour les controllers (twig etc)
class AccueilController extends AbstractController
{
//-----------------------------------------------------------------------------------------------------------
    // localhost/CresusV1/public/Accueil

    // l'url est appelée et éxécute automatiquement la méthode définie sous la route

    // function qui récupère et affiche les types d'accompagnement de l'association
    #[Route('/accueil', name: 'Accueil')]
    public function gpAccueil(TypeDemandeRepository $TypeDemandeRepository): response
    {
        // récupère tous les articles en BDD
        $typedemande = $TypeDemandeRepository->findAll();

        return $this->render('gdpublic/page/Accueil.html.twig', [
            'typedemande' => $typedemande
        ]);
    }
//-----------------------------------------------------------------------------------------------------------
    // localhost/CresusV1/public/NousTrouver

    // l'url est appelée et éxécute automatiquement la méthode définie sous la route

    // function qui récupère et affiche les types d'accompagnement de l'association
    #[Route('/noustrouver', name: 'noustrouver')]
    public function noustrouver(): response
    {

        return $this->render('gdpublic/page/Noustrouver.html.twig', [
            'typedemande' => $typedemande
        ]);
    }
}