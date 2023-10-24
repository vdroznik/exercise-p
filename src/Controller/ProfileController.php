<?php

declare(strict_types=1);

namespace ExercisePromo\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProfileController extends Controller
{
    public function index(Request $request, Response $response, $args): Response
    {
        return $this->view->render(
            $response,
            "profile/index.html.php",
            [
                'username' => $this->session->get('userId'),
            ]
        );
    }

    public function getPromo(Request $request, Response $response): Response
    {

    }
}
