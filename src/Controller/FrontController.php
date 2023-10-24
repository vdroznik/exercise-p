<?php

declare(strict_types=1);

namespace ExercisePromo\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FrontController extends Controller
{
    public function index(Request $request, Response $response, $args): Response
    {
        return $this->view->render($response, "front/index.html.php", $args);
    }
}
