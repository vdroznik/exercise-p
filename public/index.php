<?php

use DI\Container;
use ExercisePromo\Controller\FrontController;
use ExercisePromo\Controller\LoginController;
use ExercisePromo\Controller\ProfileController;
use Odan\Session\Middleware\SessionStartMiddleware;
use Odan\Session\PhpSession;
use Odan\Session\SessionInterface;
use Odan\Session\SessionManagerInterface;
use Psr\Container\ContainerInterface;
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
]);
AppFactory::setContainer($container);
$app = AppFactory::create();

$app->get('/', [FrontController::class, 'index']);
$app->group('/profile', function () use ($app) {
    $app->post('/profile/check-login', [LoginController::class, 'checkLogin']);
    $app->get('/profile', [ProfileController::class, 'index']);
})->add(SessionStartMiddleware::class);

$app->run();
