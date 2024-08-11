<?php


// permet de déclarer des types pour chacune des variables INT ...
declare(strict_types=1);

// on crée un namespace qui permet d'identifier le chemin afin d'utiliser la classe actuelle
namespace App\Controller;

// on appelle le chemin (namespace) des classes utilisées et symfony fera le require de ces classes

use App\Entity\Formulaire;
use App\Entity\Site;
use App\Form\FormulaireType;
use App\Repository\SiteRepository;
use App\Repository\TypeDemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function noustrouver(SiteRepository $siteRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupére tous les sites actifs par ordre alpha
        $sites = $siteRepository->findBy(['is_actif' => true], ['nom_site' => 'ASC']);

        // Récupére l'identifiant du site sélectionné dans le formulaire twig
        $siteId = $request->query->get('site');
        $selectedSite = $siteId ? $siteRepository->find($siteId) : null;

        // Création du formulaire
        $formulaire = new Formulaire();
        $form = $this->createForm(FormulaireType::class, $formulaire);

        // Traitement de la requête du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // affecte la valeur non à `is_traite`
            $formulaire->setisTraite(false);
            // affecte la valeur du site choisi
            if ($selectedSite) {
                $formulaire->setPermanenceDemandeur($selectedSite->getNomSite());
            }

            $entityManager->persist($formulaire);
            $entityManager->flush();

            // Ajout d'un message de succès
            $this->addFlash('success', 'Demande envoyée avec succès !');

            // Redirection pour éviter la soumission multiple
            return $this->redirectToRoute('noustrouver');
        }

        return $this->render('gdpublic/page/NousTrouver.html.twig', [
            'sites' => $sites,
            'selectedSite' => $selectedSite,
            'form' => $form->createView(),
        ]);
    }
    //-----------------------------------------------------------------------------------------------------------
    // localhost/CresusV1/dilemme/

    // l'url est appelée et éxécute automatiquement la méthode définie sous la route

    // function qui r affiche les informations du jeu Dilemme
    #[Route('/dilemme', name: 'Dilemme')]
    public function gpDilemme(): response
    {


        return $this->render('gdpublic/page/Dilemme.html.twig' );
    }
//-----------------------------------------------------------------------------------------------------------
}