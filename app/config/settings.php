<?php

$dbConfig = parse_ini_file(__DIR__ . '/charlyoutils.ini', true)['database'];
return [
    'settings' => [
        'displayErrorDetails' => true,
        'charyoutils' => $dbConfig,
    ],
];
