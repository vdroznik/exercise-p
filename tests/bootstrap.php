<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../config/config.php');
$containerBuilder->addDefinitions(__DIR__ . '/../config/config_test.php');
$containerBuilder->addDefinitions(__DIR__ . '/../config/container.php');
$containerBuilder->addDefinitions(__DIR__ . '/../config/container_test.php');
$container = $containerBuilder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();
require __DIR__ . '/../config/routes.php';

$GLOBALS['container'] = $container;
$GLOBALS['app'] = $app;
