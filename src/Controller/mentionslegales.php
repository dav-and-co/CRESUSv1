<?php


// permet de déclarer des types pour chacune des variables INT ...
declare(strict_types=1);

// on crée un namespace qui permet d'identifier le chemin afin d'utiliser la classe actuelle
namespace App\Controller;

// Importation des classes nécessaires
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// on étend la class AbstractController qui permet d'utiliser des fonctions utilitaires pour les controllers (twig etc)
class mentionslegales extends AbstractController
{
 //-----------------------------------------------------------------------------------------------------------
    // Route pour afficher les mentions légales
    #[Route('/mentionslegales', name: 'mentionslegales')]
    public function legalMentions(): Response
    {
        return $this->render('gdpublic/page/mentionslegales.html.twig');
    }
}
