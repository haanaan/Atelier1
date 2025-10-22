<?php

use charlymatloc\api\actions\GetOutilsAction;
use charlymatloc\api\actions\GetPanierAction;
use charlymatloc\api\actions\ListerOutilsAction;
use charlymatloc\api\actions\InscriptionAction;
use charlymatloc\core\application\ports\api\OutilsServiceInterface;
use charlymatloc\core\application\ports\api\PanierServiceInterface;
use charlymatloc\core\application\ports\spi\repositoryInterfaces\PanierRepositoryInterface;
use charlymatloc\core\application\ports\spi\repositoryinterfaces\PDOOutilsRepositoryInterface;
use charlymatloc\core\application\usecases\OutilsService;
use charlymatloc\core\application\usecases\PanierService;
use charlymatloc\infrastructure\repositories\PDOOutilsRepository;
use charlymatloc\infrastructure\repositories\PDOPanierRepository;

return [
    // Connexion PDO
    'charlyoutils_db' => static function ($c): PDO {
        $dbConfig = $c->get('settings')['charly_db'];
        $driver = $dbConfig['driver'] ?? 'pgsql';
        $host = $dbConfig['host'] ?? 'charyoutils.db';
        $dbname = $dbConfig['dbname'] ?? 'charlyoutils';
        $user = $dbConfig['username'] ?? 'charlyoutils';
        $pass = $dbConfig['password'] ?? 'charlyoutils';
        $charset = $dbConfig['charset'] ?? 'utf8mb4';

        $dsn = $driver === 'mysql'
            ? "mysql:host={$host};dbname={$dbname};charset={$charset}"
            : "pgsql:host={$host};dbname={$dbname}";

        return new PDO($dsn, $user, $pass);
    },
    PDOOutilsRepositoryInterface::class => function ($c) {
        return new PDOOutilsRepository($c->get('charlyoutils_db'));
    },

    OutilsServiceInterface::class => function ($c) {
        return new OutilsService($c->get(PDOOutilsRepositoryInterface::class));
    },

    GetOutilsAction::class => function ($c) {
        return new GetOutilsAction($c->get(OutilsServiceInterface::class));
    },

    ListerOutilsAction::class => function ($c) {
        return new ListerOutilsAction($c->get(OutilsServiceInterface::class));
    },

    PanierRepositoryInterface::class => function ($c) {
        return new PDOPanierRepository($c->get('charlyoutils_db'));  // Assurez-vous que le repo existe
    },

    PanierServiceInterface::class => function ($c) {
        return new PanierService($c->get(PanierRepositoryInterface::class));  // Injection du repository dans le service
    },

    GetPanierAction::class => function ($c) {
        return new GetPanierAction($c->get(PanierServiceInterface::class));  // Injection du service dans l'action
    },

    InscriptionAction::class => function ($c) {
        return new InscriptionAction($c->get(\charlymatloc\core\application\usecases\RegisterUserService::class));
    },

];