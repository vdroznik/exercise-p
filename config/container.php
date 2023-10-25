<?php

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Odan\Session\PhpSession;
use Odan\Session\SessionInterface;
use Odan\Session\SessionManagerInterface;
use Psr\Container\ContainerInterface;
use Random\Engine as RandomEngine;
use Random\Engine\Secure as RandomEngineSecure;
use Slim\Views\PhpRenderer;

return [
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
];
