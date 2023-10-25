<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../config/config.php');
$containerBuilder->addDefinitions(__DIR__ . '/../config/container.php');
AppFactory::setContainer($containerBuilder->build());

$app = AppFactory::create();
require __DIR__ . '/../config/routes.php';

$app->run();
