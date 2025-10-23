<?php

$dbConfig = parse_ini_file(__DIR__ . '/charlyoutils.ini', true)['database'];
return [
    'settings' => [
        'displayErrorDetails' => true,
        'charly_db' => $dbConfig,
        'jwtSecret' => "SAdza154189652397",
    ],
];
