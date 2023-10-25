<?php

declare(strict_types=1);

namespace ExercisePromo\Controller;

use ExercisePromo\Auth\Auth;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

readonly class ProfileController
{
    public function __construct(
        private PhpRenderer $view,
        private Auth $auth,
    ) {}

    public function index(Request $request, Response $response, $args): Response
    {
        return $this->view->render(
            $response,
            "profile/index.html.php",
            [
                'username' => $this->auth->getUser()->username,
            ]
        );
    }
}
