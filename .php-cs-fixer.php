<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/config',
        __DIR__ . '/views',
        __DIR__ . '/public',
    ])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$rules = [
    '@PER' => true,
];

$config = new PhpCsFixer\Config();

return $config->setFinder($finder)
    ->setRules($rules)
    ->setUsingCache(true);
