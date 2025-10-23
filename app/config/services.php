<?php

use charlymatloc\api\actions\AddOutilToPanierAction;
use charlymatloc\api\actions\ClearPanierAction;
use charlymatloc\api\actions\GetOutilsAction;
use charlymatloc\api\actions\GetPanierAction;
use charlymatloc\api\actions\GetPanierByUserAction;
use charlymatloc\api\actions\ListerOutilsAction;
use charlymatloc\api\actions\InscriptionAction;
use charlymatloc\api\actions\RemoveOutilFromPanierAction;
use charlymatloc\core\application\ports\api\OutilsServiceInterface;
use charlymatloc\core\application\ports\api\PanierServiceInterface;
use charlymatloc\core\application\ports\spi\repositoryInterfaces\PanierRepositoryInterface;
use charlymatloc\core\application\ports\spi\repositoryinterfaces\PDOOutilsRepositoryInterface;
use charlymatloc\core\application\usecases\OutilsService;
use charlymatloc\core\application\usecases\PanierService;
use charlymatloc\infra\repositories\PDOOutilsRepository;
use charlymatloc\infra\repositories\PDOPanierRepository;

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
        return new PDOPanierRepository($c->get('charlyoutils_db'));  
    },

    PanierServiceInterface::class => function ($c) {
        return new PanierService($c->get(PanierRepositoryInterface::class));  
    },

    GetPanierAction::class => function ($c) {
        return new GetPanierAction($c->get(PanierServiceInterface::class)); 
    },

    InscriptionAction::class => function ($c) {
        return new InscriptionAction($c->get(\charlymatloc\core\application\usecases\RegisterUserService::class));
    },

    GetPanierByUserAction::class => function ($c) {
    return new GetPanierByUserAction($c->get(PanierServiceInterface::class));
   },
   AddOutilToPanierAction::class =>function ($c) {
    return new AddOutilToPanierAction($c->get(PanierServiceInterface::class));
   },

   RemoveOutilFromPanierAction::class => function ($c) {
    return new RemoveOutilFromPanierAction($c->get(PanierServiceInterface::class));
   },

   ClearPanierAction::class => function ($c) {
    return new ClearPanierAction($c->get(PanierServiceInterface::class));
   },



];