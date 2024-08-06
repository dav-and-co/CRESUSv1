<?php

namespace App\Controller\admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{

    #[Route('/admin/users/insert', 'admin_insert_user')]
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
    }<?php
