<?php

use Doctrine\DBAL\Connection;
use Odan\Session\MemorySession;
use Odan\Session\SessionInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Random\Engine as RandomEngine;
use Slim\Psr7\Factory\ServerRequestFactory;

return [
    ServerRequestFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(ServerRequestFactory::class);
    },
    PDO::class => function (ContainerInterface $container) {
        return $container->get(Connection::class)->getNativeConnection();
    },
    SessionInterface::class => function (ContainerInterface $container) {
        $options = [
            'name' => 'exercise-promo',
            'secure' => true,
            'httponly' => true,
            'cache_limiter' => 'nocache',
        ];

        return new MemorySession($options);
    },
    RandomEngine::class => function () {
        return new class () implements RandomEngine {
            public function generate(): string
            {
                return "42";
            }
        };
    },
];
