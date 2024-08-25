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

    // function qui permet une fois le site choisi, d'envoyer un formulaire de contact
    #[Route('/noustrouver', name: 'noustrouver')]
    public function noustrouver(SiteRepository $siteRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupére tous les sites actifs par ordre alpha
        $sites = $siteRepository->findBy(['is_actif' => true], ['nom_site' => 'ASC']);

        // Récupére l'identifiant du site sélectionné dans le formulaire twig-get
        $siteId = $request->query->get('site');
        $selectedSite = $siteId ? $siteRepository->find($siteId) : null;

        // Création du formulaire
        $formulaire = new Formulaire();
        $form = $this->createForm(FormulaireType::class, $formulaire);

        // Traitement de la requête du formulaire-post
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // affecte la valeur non à `is_traite`
            $formulaire->setisTraite(false);

            $nomDemandeur = strip_tags($formulaire->getNomDemandeur());
            $formulaire->setNomDemandeur($nomDemandeur);
            $prenomDemandeur = strip_tags($formulaire->getPrenomDemandeur());
            $formulaire->setPrenomDemandeur($prenomDemandeur);
            $telephoneDemandeur = strip_tags($formulaire->getTelephoneDemandeur());
            $formulaire->setTelephoneDemandeur($telephoneDemandeur);
            $descriptionBesoin = strip_tags($formulaire->getDescriptionBesoin());
            $formulaire->setDescriptionBesoin($descriptionBesoin);

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

    // function qui affiche les informations du jeu Dilemme
    #[Route('/dilemme', name: 'Dilemme')]
    public function gpDilemme(): response
    {


        return $this->render('gdpublic/page/Dilemme.html.twig' );
    }
//-----------------------------------------------------------------------------------------------------------
// localhost/CresusV1/Microcredit/

    // l'url est appelée et éxécute automatiquement la méthode définie sous la route

    // function qui affiche les informations du microcrédit
    #[Route('/microcredit', name: 'Microcredit')]
    public function gpMicroCredit(): response
    {


        return $this->render('gdpublic/page/Microcredit.html.twig' );
    }
//-----------------------------------------------------------------------------------------------------------
// localhost/CresusV1/PCB/

    // l'url est appelée et éxécute automatiquement la méthode définie sous la route

    // function qui affiche les informations du point conseil budget
    #[Route('/PCB', name: 'PCB')]
    public function gpPCB(): response
    {


        return $this->render('gdpublic/page/PCB.html.twig' );
    }
//-----------------------------------------------------------------------------------------------------------
// localhost/CresusV1/surendettement/

    // l'url est appelée et éxécute automatiquement la méthode définie sous la route

    // function qui affiche les informations du surendettement
    #[Route('/surendettement', name: 'Surendettement')]
    public function gpSurendettement(): response
    {


        return $this->render('gdpublic/page/Surendettement.html.twig' );
    }
//-----------------------------------------------------------------------------------------------------------
// localhost/CresusV1/aider/

    // l'url est appelée et éxécute automatiquement la méthode définie sous la route

    // function qui affiche les informations du surendettement
    #[Route('/aider', name: 'Aider')]
    public function gpAider(): response
    {


        return $this->render('gdpublic/page/Aider.html.twig' );
    }
//-----------------------------------------------------------------------------------------------------------
// localhost/CresusV1/nous/

    // l'url est appelée et éxécute automatiquement la méthode définie sous la route

    // function qui affiche les informations du surendettement
    #[Route('/nous', name: 'nous')]
    public function gpNous(): response
    {


        return $this->render('gdpublic/page/Nous.html.twig' );
    }




}