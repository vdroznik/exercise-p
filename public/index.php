<?php

use DI\ContainerBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
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

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__.'/../config/config.php');
$containerBuilder->addDefinitions([
    PhpRenderer::class => function (ContainerInterface $container) {
        return new PhpRenderer(
            templatePath: __DIR__ . '/../views',
            attributes: ['flash' => $container->get(SessionInterface::class)->getFlash()],
            layout: 'layout.html.php',
        );
    },

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
    Connection::class => function (ContainerInterface $container) {
        return DriverManager::getConnection($container->get('db'));
    },
]);

AppFactory::setContainer($containerBuilder->build());
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
