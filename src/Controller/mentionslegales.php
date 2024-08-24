<?php


// permet de déclarer des types pour chacune des variables INT ...
declare(strict_types=1);

// on crée un namespace qui permet d'identifier le chemin afin d'utiliser la classe actuelle
namespace App\Controller;

// on appelle le chemin (namespace) des classes utilisées et symfony fera le require de ces classes

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class mentionslegales extends AbstractController
{
    #[Route('/mentionslegales', name: 'mentionslegales')]

    public function legalMentions(): Response
    {
        return $this->render('gdpublic/page/mentionslegales.html.twig');
    }
}
