<?php

namespace ExercisePromo\Middleware;

use ExercisePromo\Auth\Auth;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        protected Auth $auth,
    ) {}

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        if (!$this->auth->isAuthorized()) {
            $response = new Response();

            return $response
                ->withHeader('Location', '/')
                ->withStatus(302);
        }

        return $handler->handle($request);
    }
}
