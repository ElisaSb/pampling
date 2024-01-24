<?php

declare(strict_types=1);

namespace App\Listener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Twig\Environment;

class UserNotFoundExceptionListener
{
    private Environment $twig;
    private const MESSAGE = 'El usuario con el id %s no ha sido encontrado.';

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $response = $this->twig->render('user/notfound.html.twig', [
            'message' => \sprintf(self::MESSAGE, $event->getRequest()->attributes->get('id'))
        ]);
        $event->setResponse(new Response($response));

    }
}