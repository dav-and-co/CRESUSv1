<?php

// permet de déclarer des types pour chacune des variables INT ...
declare(strict_types=1);

// on crée un namespace qui permet d'identifier le chemin afin d'utiliser la classe actuelle
namespace App\Controller;


// Importation des classes nécessaires pour ce contrôleur
use App\Entity\PermananceSite;
use App\Entity\RendezVous;
use App\Entity\User;
use App\Form\PermananceSiteType;
use App\Form\RendezVousType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class RendezVousController extends AbstractController
{

//--------------------------------------------------------------------------------------------------------------
    #[Route('/benevole/rendezvous/edit/{id}', name: 'edit_rendezvous')]
    public function edit(RendezVous $rendezVous, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RendezVousType::class, $rendezVous);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Rendez-vous mis à jour avec succès.');
            return $this->redirectToRoute('affichageDemande', ['id' => $rendezVous->getDemande()->getId()]);
        }

        return $this->render('interne/page/modifRendezVous.html.twig', [
            'form' => $form->createView(),
            'rendezvous' => $rendezVous,
        ]);
    }

//--------------------------------------------------------------------------------------------------------------
    #[Route('/benevole/insertpermanences', name: 'insertpermanence')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Créez une nouvelle instance de l'entité PermanenceSite
        $permanenceSite = new PermananceSite();

        // Récupérez la liste des utilisateurs actifs
        $users = $entityManager->getRepository(User::class)->findBy(['isActif' => true]);

        // Créez le formulaire et passez la liste des utilisateurs actifs comme option
        $form = $this->createForm(PermananceSiteType::class, $permanenceSite, [
            'activeUsers' => $users, // Passez les utilisateurs actifs ici
        ]);

        // Traitez la requête du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persistez et sauvegardez l'entité
            $entityManager->persist($permanenceSite);
            $entityManager->flush();

            return $this->redirectToRoute('benevoleAccueil'); // Redirection vers la liste des permanences
        }

        return $this->render('interne/page/insertpermanence.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
