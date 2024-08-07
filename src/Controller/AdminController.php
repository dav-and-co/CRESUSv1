<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ArticleType;
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

        if ($request->getMethod() === "POST") {
            $username = $request->request->get('username');
            $role = $request->request->get('roles');
            $password = $request->request->get('password');
            $nomUser = $request->request->get('nomUser');
            $prenomUser = $request->request->get('prenomUser');
            $telPerso = $request->request->get('telPerso');
            $telAssoc = $request->request->get('telAssoc');
            $mailPerso = $request->request->get('mailPerso');
            $isActif = true;


            $user = new User();

            try {
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $password
                );

                $user->setUsername($username);
                $user->setRoles(['$role']);
                $user->setPassword($hashedPassword);
                $user->setNomUser($nomUser);
                $user->setPrenomUser($prenomUser);
                $user->setTelPerso($telPerso);
                $user->setTelAssoc($telAssoc);
                $user->setMailPerso($mailPerso);
                $user->setActif($isActif);


                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'utilisateur créé');

            } catch (\Exception $exception) {
                // attention, messages erreur brut
                $this->addFlash('error', $exception->getMessage());
            }
        }

        return $this->render('admin/page/user/insert_user.html.twig');
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
    public function updateBenevole(int $id, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $benevole = $userRepository->find($id);

        $benevoleCreateForm = $this->createForm(ArticleType::class, $benevole);

        $benevoleCreateForm->handleRequest($request);

        if ($benevoleCreateForm->isSubmitted() && $benevoleCreateForm->isValid()) {
            $entityManager->persist($benevole);
            $entityManager->flush();

            $this->addFlash('success', 'article enregistré');
        }

        $benevoleCreateFormView = $benevoleCreateForm->createView();

        return $this->render('admin/page/update_article.html.twig', [
            'benevoleForm' => $benevoleCreateFormView
        ]);










}


