<?php

declare(strict_types=1);

namespace ExercisePromo\Controller;

use Odan\Session\SessionInterface;
use Psr\Container\ContainerInterface;
use Slim\Views\PhpRenderer;

class Controller
{
    public function __construct(
        protected PhpRenderer $view,
        protected SessionInterface $session,
        protected ContainerInterface $container,
    ) {}
}
