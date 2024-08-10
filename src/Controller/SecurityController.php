<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\IpPcRepository;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
class SecurityController extends AbstractController
{
    private $ipPcRepository;
    public function __construct(IpPcRepository $ipPcRepository)
    {
        $this->ipPcRepository = $ipPcRepository;
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        $clientIp = $request->getClientIp();
        $error1 = null;

        // Vérifier si l'adresse IP est autorisée
        // cherche s'il y a un enregistrement dans la table ip qui correspond à l'id PC de la requete
        if (!$this->ipPcRepository->findOneBy(['identifiant_PC' => $clientIp])) {

            $lastUsername = null;
            $error = null;
            $error1 = 'Accès refusé. Merci de contacter un administrateur.';

            return $this->render('security/login.html.twig', [
                'last_username' => $lastUsername,
                'error' => $error,
                'error1' => $error1,
            ]);

        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'error1' => $error1,
        ]);



    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

