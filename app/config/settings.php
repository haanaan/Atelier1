<?php

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\FilterHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'logError'            => true,
        'logErrorDetails'     => true,
        // Database settings
        'toubiprati.db' => [
            'host'    => getenv('TOUBIPRATI_DB_HOST')    ?: 'DB_HOST_PLACEHOLDER',
            'user'    => getenv('TOUBIPRATI_DB_USER')    ?: 'DB_USER_PLACEHOLDER',
            'pass'    => getenv('TOUBIPRATI_DB_PASS')    ?: 'DB_PASS_PLACEHOLDER',
            'dbname'  => getenv('TOUBIPRATI_DB_NAME')    ?: 'DB_NAME_PLACEHOLDER',
            'charset' => getenv('TOUBIPRATI_DB_CHARSET') ?: 'utf8mb4',
        ],
    ],

    LoggerInterface::class => function (ContainerInterface $c) {
        $logsDir = __DIR__ . '/../var/logs';

        if (!is_dir($logsDir)) {
            mkdir($logsDir, 0775, true);
        }

        $logger = new Logger('app');

        // fichier pour DEBUG..INFO (ici on garde DEBUG..INFO pour inclure INFO)
        $infoStream = new StreamHandler($logsDir . '/logs.log', Logger::DEBUG);
        $infoFilter = new FilterHandler($infoStream, Logger::DEBUG, Logger::INFO);

        // fichier pour WARNING et plus
        $errorStream = new StreamHandler($logsDir . '/errors.log', Logger::WARNING);

        // (optionnel) formatter
        $formatter = new LineFormatter(null, null, true, true);
        $infoStream->setFormatter($formatter);
        $errorStream->setFormatter($formatter);

        $logger->pushHandler($infoFilter);
        $logger->pushHandler($errorStream);

        return $logger;
    },

    // Connexion PDO pour la base "praticien"
    'db.praticien' => static function (): PDO {
        $driver = $_ENV['prat.driver'] ?? 'pgsql';
        $host = $_ENV['prat.host'] ?? 'toubiprati.db';
        $db = $_ENV['prat.database'] ?? 'toubiprat';
        $user = $_ENV['prat.username'] ?? 'toubiprat';
        $pass = $_ENV['prat.password'] ?? 'toubiprat';
        $charset = 'utf8mb4';

        $dsn = $driver === 'mysql'
            ? "mysql:host={$host};dbname={$db};charset={$charset}"
            : "pgsql:host={$host};dbname={$db}";

        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    },


];