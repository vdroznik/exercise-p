<?php

use ExercisePromo\Controller\AuthController;
use ExercisePromo\Controller\FrontController;
use ExercisePromo\Controller\ProfileController;
use ExercisePromo\Controller\PromoController;
use ExercisePromo\Middleware\AuthMiddleware;
use Odan\Session\Middleware\SessionStartMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

/**
 * @var App $app
 */
$app->get('/', [FrontController::class, 'index']);

$app->post('/check-login', [AuthController::class, 'checkLogin'])
    ->add(SessionStartMiddleware::class);

$app->group('/profile', function (RouteCollectorProxy $group) {
    $group->get('', [ProfileController::class, 'index']);
    $group->get('/getpromo', [PromoController::class, 'getPromo']);
    $group->get('/logout', [AuthController::class, 'logout']);
})->add(AuthMiddleware::class)
    ->add(SessionStartMiddleware::class);
