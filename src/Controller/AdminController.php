<?php

namespace App\Controller;

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
    // création d'un nouveau benevole
//-----------------------------------------------------------------------------------------------------------
    #[Route('/admin/user/insert', 'admin_insert_user')]
    public function insertAdmin(UserPasswordHasherInterface $passwordHasher, Request $request, EntityManagerInterface $entityManager)
    {

        $user = new User();

        $userCreateForm = $this->createForm(userType::class, $user);

        $userCreateForm->handleRequest($request);

        if ($userCreateForm->isSubmitted() && $userCreateForm->isValid()) {

            $password = $userCreateForm->get('password')->getData();

             $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $password,
                );
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Bénévole bien ajouté !');
            return $this->redirectToRoute('admin_insert_user');
        }

        $userCreateFormView = $userCreateForm->createView();


        return $this->render('interne/page/insertBenevole.html.twig', [
            'benevoleForm' => $userCreateFormView
        ]);


    }

//-----------------------------------------------------------------------------------------------------------
    // liste des benevoles
    #[Route('/admin/benevoles', name: 'listbenevoles')]
    public function GdPublicArticles(UserRepository $UserRepository, Request $request)
    {
        $tri = $request->query->get('tri');
        $ordre = $request->query->get('ordre');

        if (!$tri) {
            $tri = 'username';
            $ordre = 'DESC';
        }

        // récupère tous les articles en BDD triés par ASC ou DESC
        $benevoles = $UserRepository->findBy([], [$tri => $ordre]);

        return $this->render('interne/page/listBenevoles.html.twig', [
            'benevoles' => $benevoles
        ]);
    }

    //-----------------------------------------------------------------------------------------------------------
    // modification d'un bénévole
    #[Route('/admin/benevole/update/{id}', 'updatebenevole')]
    public function updateBenevole(int $id, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository,UserPasswordHasherInterface $passwordHasher)
    {
        $benevole = $userRepository->find($id);
        $oldMdp = $benevole->getPassword();

        $benevoleModifForm = $this->createForm(UserType::class, $benevole);

        $benevoleModifForm->handleRequest($request);

        if ($benevoleModifForm->isSubmitted() && $benevoleModifForm->isValid()) {

            $password = $benevoleModifForm->get('password')->getData();

                if ($password) {
                    $hashedPassword = $passwordHasher->hashPassword(
                        $benevole,
                        $password,
                    );

                    $benevole->setPassword($hashedPassword);
                } else {
                    $benevole->setPassword( $oldMdp);
                }

                $entityManager->persist($benevole);
                $entityManager->flush();

                $this->addFlash('success', 'modification du bénévole enregistrée');
        }

        $benevoleModifFormView = $benevoleModifForm->createView();

        return $this->render('interne/page/modifBenevole.html.twig', [
            'benevoleForm' => $benevoleModifFormView
        ]);
    }


}


