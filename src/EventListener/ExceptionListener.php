<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment as Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ExceptionListener
{
    private $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        // Vérifiez s'il s'agit d'une 404
        if ($exception instanceof NotFoundHttpException) {
            // Créez une réponse Twig personnalisée pour l'erreur 404
            $response = new Response(
                $this->twig->render('bundles/TwigBundle/Exception/error404.html.twig'),
                Response::HTTP_NOT_FOUND
            );

            // Définit cette réponse dans l'événement
            $event->setResponse($response);
        }
    }
}