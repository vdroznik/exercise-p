<?php

use DI\Container;
use ExercisePromo\Controller\FrontController;
use ExercisePromo\Controller\AuthController;
use ExercisePromo\Controller\ProfileController;
use ExercisePromo\Middleware\AuthMiddleware;
use Odan\Session\Middleware\SessionStartMiddleware;
use Odan\Session\PhpSession;
use Odan\Session\SessionInterface;
use Odan\Session\SessionManagerInterface;
use Psr\Container\ContainerInterface;
use Random\Engine as RandomEngine;
use Random\Engine\Secure as RandomEngineSecure;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container([
    PhpRenderer::class => DI\create()->constructor(
        templatePath: __DIR__ . '/../views',
        layout: 'layout.html.php',
    ),
    SessionManagerInterface::class => function (ContainerInterface $container) {
        return $container->get(SessionInterface::class);
    },
    SessionInterface::class => function (ContainerInterface $container) {
        $options = [
            'name' => 'exercise-promo',
            'secure' => true,
            'httponly' => true,
            'cache_limiter' => 'nocache',
        ];

        return new PhpSession($options);
    },
    RandomEngine::class => DI\get(RandomEngineSecure::class),
]);
AppFactory::setContainer($container);
$app = AppFactory::create();

$app->get('/', [FrontController::class, 'index']);

$app->post('/check-login', [AuthController::class, 'checkLogin'])
    ->add(SessionStartMiddleware::class);

$app->group('/profile', function () use ($app) {
    $app->get('/profile', [ProfileController::class, 'index']);
    $app->get('/profile/getpromo', [ProfileController::class, 'getPromo']);
    $app->get('/profile/logout', [AuthController::class, 'logout']);
})->add(AuthMiddleware::class)
    ->add(SessionStartMiddleware::class);

$app->run();
