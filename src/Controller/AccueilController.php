<?php


// permet de déclarer des types pour chacune des variables INT ...
declare(strict_types=1);

// on crée un namespace qui permet d'identifier le chemin afin d'utiliser la classe actuelle
namespace App\Controller;

//Importation des classes nécessaires avec leurs namespaces pour pouvoir les utiliser
use App\Entity\Formulaire;
use App\Form\FormulaireType;
use App\Repository\SiteRepository;
use App\Repository\TypeDemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


// on étend la class AbstractController qui permet d'utiliser des fonctions utilitaires pour les controllers (twig etc)
class AccueilController extends AbstractController
{
//-----------------------------------------------------------------------------------------------------------
// localhost/CresusV1/public/
    // fonction qui récupère et affiche la landingpage
    #[Route('/', name: 'Accueil')]
    public function gpAccueil(TypeDemandeRepository $TypeDemandeRepository): response
    {
         // récupère tous les articles en BDD
        $typedemande = $TypeDemandeRepository->findAll();

        // Rend la vue Twig en y passant données récupérées
        return $this->render('gdPublic/page/Accueil.html.twig', [
            'typedemandes' => $typedemande
        ]);
    }

//-----------------------------------------------------------------------------------------------------------
// localhost/CresusV1/noustrouver
    // fonction qui permet une fois le site choisi, d'afficher et d'envoyer un formulaire de contact
    #[Route('/noustrouver', name: 'noustrouver')]
    public function noustrouver(SiteRepository $siteRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupére tous les sites actifs par ordre alpha
        $sites = $siteRepository->findBy(['is_actif' => true], ['nom_site' => 'ASC']);

        // Récupére l'identifiant du site sélectionné dans le formulaire twig-get
        $siteId = $request->query->get('site');

        // Si un identifiant est sélectionné, récupère le site correspondant, sinon null
        $selectedSite = $siteId ? $siteRepository->find($siteId) : null;

        // Création d'un nouvel objet Formulaire pour stocker les données du formulaire
        $formulaire = new Formulaire();
        // Création du formulaire basé sur la classe FormulaireType et l'objet $formulaire
        $form = $this->createForm(FormulaireType::class, $formulaire);

        // Gère la requête POST si le formulaire a été soumis
        $form->handleRequest($request);

        // Vérifie si le formulaire a été soumis et s'il est valide
        if ($form->isSubmitted() && $form->isValid()) {

            // affecte la valeur non à `is_traite`
            $formulaire->setisTraite(false);

            // contrôle sanitaire des données saisies
            $formulaire->setNomDemandeur(strip_tags($formulaire->getNomDemandeur()));
            $formulaire->setPrenomDemandeur(strip_tags($formulaire->getPrenomDemandeur()));
            $formulaire->setMailDemandeur(strip_tags($formulaire->getMailDemandeur()));
            $formulaire->setTelephoneDemandeur(strip_tags($formulaire->getTelephoneDemandeur()));
            if ($formulaire->getDescriptionBesoin()) {
                $formulaire->setDescriptionBesoin(strip_tags($formulaire->getDescriptionBesoin()));
            }

            // affecte la valeur du site choisi
            if ($selectedSite) {
                $formulaire->setPermanenceDemandeur($selectedSite->getNomSite());
            }

            //persite et sauvegarde les changements en base de données
            $entityManager->persist($formulaire);
            $entityManager->flush();

            // Ajout d'un message de succès
            $this->addFlash('success', 'Demande envoyée avec succès !');

            // Redirection pour éviter la soumission multiple
            return $this->redirectToRoute('noustrouver');
        }
        // affiche la vue Twig avec les paramètres
        return $this->render('gdPublic/page/NousTrouver.html.twig', [
            'sites' => $sites,
            'selectedSite' => $selectedSite,
            'form' => $form->createView(),
        ]);
    }

    //-----------------------------------------------------------------------------------------------------------
    // localhost/CresusV1/benevole/

    // function qui affiche le html de demande de bénévoles
    #[Route('/Benevole', name: 'Benevole')]
    public function gpBenevole(): response
    {
        return $this->render('gdPublic/page/benevole.html.twig' );
    }

    //-----------------------------------------------------------------------------------------------------------
    // localhost/CresusV1/dilemme/

    // function qui affiche les informations du jeu Dilemme
    #[Route('/dilemme', name: 'Dilemme')]
    public function gpDilemme(): response
    {
        return $this->render('gdPublic/page/Dilemme.html.twig' );
    }

//-----------------------------------------------------------------------------------------------------------
// localhost/CresusV1/Microcredit/
    // function qui affiche les informations du microcrédit
    #[Route('/microcredit', name: 'Microcredit')]
    public function gpMicroCredit(): response
    {
        return $this->render('gdPublic/page/Microcredit.html.twig' );
    }
//-----------------------------------------------------------------------------------------------------------
// localhost/CresusV1/PCB/
    // function qui affiche les informations du point conseil budget
    #[Route('/PCB', name: 'PCB')]
    public function gpPCB(): response
    {
        return $this->render('gdPublic/page/PCB.html.twig' );
    }
//-----------------------------------------------------------------------------------------------------------
// localhost/CresusV1/surendettement/
    // function qui affiche les informations du surendettement
    #[Route('/surendettement', name: 'Surendettement')]
    public function gpSurendettement(): response
    {
        return $this->render('gdPublic/page/Surendettement.html.twig' );
    }

//-----------------------------------------------------------------------------------------------------------
// localhost/CresusV1/aider/
    // function qui affiche les informations des différents types d'aides de l'assocation
    #[Route('/aider', name: 'Aider')]
    public function gpAider(): response
    {
        return $this->render('gdPublic/page/Aider.html.twig' );
    }

//-----------------------------------------------------------------------------------------------------------
// localhost/CresusV1/nous/
    // function qui affiche les informations sur l'association
    #[Route('/nous', name: 'nous')]
    public function gpNous(): response
    {
        return $this->render('gdPublic/page/Nous.html.twig' );
    }
//-----------------------------------------------------------------------------------------------------------
}