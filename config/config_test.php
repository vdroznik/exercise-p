<?php

return [
    'db' => [
        'dbname' => getenv('MYSQL_DATABASE') . '_test',
        'user' => getenv('MYSQL_USER'),
        'password' => getenv('MYSQL_PASSWORD'),
        'host' => getenv('MYSQL_HOST'),
        'driver' => 'pdo_mysql',
    ],
];
