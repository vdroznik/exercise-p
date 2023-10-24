<?php

declare(strict_types=1);

namespace ExercisePromo\Controller;

use Psr\Container\ContainerInterface;
use Slim\Views\PhpRenderer;

class Controller
{
    public function __construct(
        protected PhpRenderer $view,
        protected ContainerInterface $container,
    ) {}
}
