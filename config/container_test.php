<?php

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Slim\Psr7\Factory\ServerRequestFactory;

return [
    ServerRequestFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(ServerRequestFactory::class);
    },
];