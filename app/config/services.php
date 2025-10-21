<?php

use charlymatloc\api\actions\GetOutilsAction;
use charlymatloc\api\actions\ListerOutilsAction;
use charlymatloc\api\actions\ObtenirOutilAction;
use charlymatloc\core\application\ports\api\ServiceOutilsInterface;
use charlymatloc\core\application\ports\spi\repositoryinterfaces\PDOOutilsRepositoryInterface;
use charlymatloc\core\application\usecases\ServiceOutils;
use charlymatloc\infra\repositories\PDOOutilsRepository;

return [
    // Connexion PDO
    'charlyoutils_db' => static function ($c): PDO {
        $dbConfig = $c->get('settings')['charly_db'];
        $driver  = $dbConfig['driver'] ?? 'pgsql';
        $host    = $dbConfig['host'] ?? 'charyoutils.db';
        $dbname  = $dbConfig['dbname'] ?? 'charlyoutils';
        $user    = $dbConfig['username'] ?? 'charlyoutils';
        $pass    = $dbConfig['password'] ?? 'charlyoutils';
        $charset = $dbConfig['charset'] ?? 'utf8mb4';

        $dsn = $driver === 'mysql'
            ? "mysql:host={$host};dbname={$dbname};charset={$charset}"
            : "pgsql:host={$host};dbname={$dbname}";

        return new PDO($dsn, $user, $pass);
    },
     PDOOutilsRepositoryInterface::class => function ($c) {
        return new PDOOutilsRepository($c->get('charlyoutils_db')); 
    },

    // Services
    
    
    // Actions
    GetOutilsAction::class => function ($c) {
        return new GetOutilsAction($c->get(ServiceOutilsInterface::class));
    },

];