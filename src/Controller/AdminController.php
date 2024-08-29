<?php

namespace App\Controller;

// Importation des classes nécessaires

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

//-----------------------------------------------------------------------------------------------------------
    // Route pour créer un nouveau bénévole (user) benevole
    #[Route('/admin/user/insert', 'admin_insert_user')]
    public function insertBenevole(UserPasswordHasherInterface $passwordHasher, Request $request, EntityManagerInterface $entityManager)
    {
        // Création d'une nouvelle instance de l'entité User
        $user = new User();
        // Création du formulaire lié à l'entité User
        $userCreateForm = $this->createForm(userType::class, $user);

        // Traitement de la requête HTTP
        $userCreateForm->handleRequest($request);
        // Vérification si le formulaire a été soumis et est valide
        if ($userCreateForm->isSubmitted() && $userCreateForm->isValid()) {
            // Récupération du mot de passe saisi dans le formulaire
            $password = $userCreateForm->get('password')->getData();
            // Hachage du mot de passe pour le sécuriser avant de le stocker en base de données
             $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $password,
                );
            // Définition du mot de passe haché dans l'instance User
            $user->setPassword($hashedPassword);

            // Persistance et sauvegarde des modifications en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Ajout d'un message de succès dans la session
            $this->addFlash('success', 'Bénévole bien ajouté !');

            // Redirection vers la même route pour éviter une soumission multiple
            return $this->redirectToRoute('admin_insert_user');
        }

        // Création de la vue du formulaire pour l'affichage dans le template
        $userCreateFormView = $userCreateForm->createView();

        // affiche la vue Twig en y passant données récupérées
        return $this->render('interne/page/insertBenevole.html.twig', [
            'benevoleForm' => $userCreateFormView
        ]);
    }

//-----------------------------------------------------------------------------------------------------------
    // Route pour afficher la liste des bénévoles
    #[Route('/admin/benevoles', name: 'listbenevoles')]
    public function listBenevoles(UserRepository $UserRepository, Request $request)
    {
        // Récupération des paramètres de tri depuis la requête GET (si présents)
        $tri = $request->query->get('tri');
        $ordre = $request->query->get('ordre');
        // Définition des valeurs par défaut si le tri n'est pas spécifié
        if (!$tri) {
            $tri = 'username';
            $ordre = 'ASC';
        }
        // récupère tous les articles en BDD triés par ASC ou DESC
        $benevoles = $UserRepository->findBy([], [$tri => $ordre]);

        // affiche la vue Twig en y passant données récupérées
        return $this->render('interne/page/listBenevoles.html.twig', [
            'benevoles' => $benevoles
        ]);
    }

//-----------------------------------------------------------------------------------------------------------
    // Route pour la modification d'un bénévole existant
    #[Route('/admin/benevole/update/{id}', 'updatebenevole')]
    public function updateBenevole(int $id, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository,UserPasswordHasherInterface $passwordHasher)
    {
        // Récupération du bénévole depuis la base de données en fonction de son ID - donnée poussée dans la route
        $benevole = $userRepository->find($id);

        // Sauvegarde du mot de passe actuel pour le réutiliser si non modifié
        $oldMdp = $benevole->getPassword();

        // Création du formulaire de modification lié à l'entité User
        $benevoleModifForm = $this->createForm(UserType::class, $benevole);

        // Traitement de la requête HTTP
        $benevoleModifForm->handleRequest($request);

        // Vérification si le formulaire a été soumis et est valide
        if ($benevoleModifForm->isSubmitted() && $benevoleModifForm->isValid()) {
            // Récupération du nouveau mot de passe saisi (s'il y en a un)
            $password = $benevoleModifForm->get('password')->getData();
            // Si un nouveau mot de passe a été saisi, on le hache et l'enregistre
            if ($password) {
                $hashedPassword = $passwordHasher->hashPassword(
                    $benevole,
                    $password,
                );
                $benevole->setPassword($hashedPassword);
            } else {
                // Sinon, on conserve l'ancien mot de passe
                $benevole->setPassword( $oldMdp);
            }
            // Persistance et sauvegarde des modifications en base de données
            $entityManager->persist($benevole);
            $entityManager->flush();

            // Ajout d'un message de succès dans la session
            $this->addFlash('success', 'modification du bénévole enregistrée');
        }

        $benevoleModifFormView = $benevoleModifForm->createView();

        // affiche la vue Twig en y passant données récupérées
        return $this->render('interne/page/modifBenevole.html.twig', [
            'benevoleForm' => $benevoleModifFormView,
            'username' => $benevole->getUsername()
        ]);
    }
//-----------------------------------------------------------------------------------------------------------

}


