<?php

declare(strict_types=1);

namespace ExercisePromo\Controller;

use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController
{
    public function __construct(
        protected SessionInterface $session,
    ) {}

    public function checkLogin(Request $request, Response $response): Response
    {
        $this->session->set('userId', 102);

        return $response
            ->withHeader('Location', '/profile')
            ->withStatus(302);
    }

    public function logout(Request $request, Response $response): Response
    {
        $this->session->clear();

        return $response
            ->withHeader('Location', '/')
            ->withStatus(302);
    }
}
