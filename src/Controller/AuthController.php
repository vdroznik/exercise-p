<?php

declare(strict_types=1);

namespace ExercisePromo\Controller;

use ExercisePromo\Auth\Auth;
use ExercisePromo\Entity\User;
use ExercisePromo\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController
{
    public function __construct(
        protected Auth $auth,
        protected UserRepository $userRepo,
    ) {}

    public function checkLogin(Request $request, Response $response): Response
    {
        $params = (array)$request->getParsedBody();
        $username = $params['username'];

        $user = $this->userRepo->findByUsername($username);
        if (!$user) {
            $this->userRepo->create(new User(username: $username));
            $user = $this->userRepo->findByUsername($username);
        }

        $this->auth->login($user);

        return $response
            ->withHeader('Location', '/profile')
            ->withStatus(302);
    }

    public function logout(Request $request, Response $response): Response
    {
        $this->auth->logout();

        return $response
            ->withHeader('Location', '/')
            ->withStatus(302);
    }
}
