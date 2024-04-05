<?php

$cfg['Servers'] = [
    1 => [ #For MySQL instance inside docker compose
        'auth_type' => 'cookie',
        'host' => 'mysql',
        'user' => 'developer',
        'password' => 'developer',
    ],
    2 => [ # for remote connection
        'auth_type' => 'cookie',
        'host' => 'mysql-test',
        'user' => 'developer',
        'password' => 'developer'
    ],
    # Add other connections if needed.
];