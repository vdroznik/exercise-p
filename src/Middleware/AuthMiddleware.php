<?php

namespace ExercisePromo\Middleware;

use Odan\Session\SessionInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class AuthMiddleware
{
    public function __construct(
        protected SessionInterface $session,
    ) {}

    public function __invoke(Request $request, RequestHandlerInterface $handler): Response
    {
        if (!$this->session->get('userId')) {
            $response = new Response();

            return $response
                ->withHeader('Location', '/')
                ->withStatus(302);
        }

        return $handler->handle($request);
    }
}
