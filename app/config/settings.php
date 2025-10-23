<?php

$dbConfig = parse_ini_file(__DIR__ . '/charlyoutils.ini', true)['database'];
return [
    'settings' => [
        'displayErrorDetails' => true,
        'charly_db' => $dbConfig,
        'jwtSecret' => "511e532e2b5b5842",
    ],
];
