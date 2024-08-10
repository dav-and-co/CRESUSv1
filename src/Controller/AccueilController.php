<?php


// permet de déclarer des types pour chacune des variables INT ...
declare(strict_types=1);

// on crée un namespace qui permet d'identifier le chemin afin d'utiliser la classe actuelle
namespace App\Controller;

// on appelle le chemin (namespace) des classes utilisées et symfony fera le require de ces classes

use App\Repository\SiteRepository;
use App\Repository\TypeDemandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


// on étend la class AbstractController qui permet d'utiliser des fonctions utilitaires pour les controllers (twig etc)
class AccueilController extends AbstractController
{
//-----------------------------------------------------------------------------------------------------------
    // localhost/CresusV1/public/

    // l'url est appelée et éxécute automatiquement la méthode définie sous la route

    // function qui récupère et affiche les types d'accompagnement de l'association
    #[Route('/', name: 'Accueil')]
    public function gpAccueil(TypeDemandeRepository $TypeDemandeRepository): response
    {
        // récupère tous les articles en BDD
        $typedemande = $TypeDemandeRepository->findAll();

        return $this->render('gdpublic/page/Accueil.html.twig', [
            'typedemandes' => $typedemande
        ]);
    }
//-----------------------------------------------------------------------------------------------------------
    // localhost/CresusV1/public/NousTrouver

    // l'url est appelée et éxécute automatiquement la méthode définie sous la route

    // function qui récupère et affiche les types d'accompagnement de l'association
    #[Route('/noustrouver', name: 'noustrouver')]
    public function noustrouver(SiteRepository $siteRepository, Request $request): Response
    {
        // Récupére tous les sites actifs par ordre alpha
        $sites = $siteRepository->findBy(['is_actif' => true], ['nom_site' => 'ASC']);

        // Récupére l'identifiant du site sélectionné dans le formulaire twig
        $selectedSiteId = $request->get('site');

        // Trouve les détails du site sélectionné, s'il existe
        $selectedSite = null;
        if ($selectedSiteId) {
            $selectedSite = $siteRepository->find($selectedSiteId);
        }

        return $this->render('gdpublic/page/NousTrouver.html.twig', [
            'sites' => $sites,
            'selectedSite' => $selectedSite,
        ]);
    }
}