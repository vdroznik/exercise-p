<?php

declare(strict_types=1);

namespace ExercisePromo\Controller;

use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LoginController
{
    public function __construct(
        protected SessionInterface $session,
    ) {}

    public function checkLogin(Request $request, Response $response): Response
    {
        $this->session->set('userId', 'test');

        return $response
            ->withHeader('Location', '/profile')
            ->withStatus(302);
    }
}
