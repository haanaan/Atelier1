<?php

use charlymatloc\api\actions\GetOutilsAction;
use charlymatloc\api\actions\GetPanierAction;
use charlymatloc\api\actions\ListerOutilsAction;
use charlymatloc\api\actions\InscriptionAction;
use charlymatloc\core\application\ports\api\OutilsServiceInterface;
use charlymatloc\core\application\ports\api\PanierServiceInterface;
use charlymatloc\core\application\ports\api\ReservationServiceInterface;
use charlymatloc\core\application\ports\spi\repositoryInterfaces\PanierRepositoryInterface;
use charlymatloc\core\application\ports\spi\repositoryinterfaces\PDOOutilsRepositoryInterface;
use charlymatloc\core\application\ports\spi\repositoryinterfaces\PDOReservationRepositoryInterface;
use charlymatloc\core\application\usecases\OutilsService;
use charlymatloc\core\application\usecases\PanierService;
use charlymatloc\core\application\usecases\ReservationService;
use charlymatloc\infrastructure\repositories\PDOOutilsRepository;
use charlymatloc\infrastructure\repositories\PDOPanierRepository;
use charlymatloc\infra\repositories\PDOReservationRepository;

return [

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

    // 🧰 OUTILS
    PDOOutilsRepositoryInterface::class => fn($c) =>
        new PDOOutilsRepository($c->get('charlyoutils_db')),

    OutilsServiceInterface::class => fn($c) =>
        new OutilsService($c->get(PDOOutilsRepositoryInterface::class)),

    GetOutilsAction::class => fn($c) =>
        new GetOutilsAction($c->get(OutilsServiceInterface::class)),

    ListerOutilsAction::class => fn($c) =>
        new ListerOutilsAction($c->get(OutilsServiceInterface::class)),

    // 🛒 PANIER
    PanierRepositoryInterface::class => fn($c) =>
        new PDOPanierRepository($c->get('charlyoutils_db')),

    PanierServiceInterface::class => fn($c) =>
        new PanierService($c->get(PanierRepositoryInterface::class)),

    GetPanierAction::class => fn($c) =>
        new GetPanierAction($c->get(PanierServiceInterface::class)),

    // 👤 INSCRIPTION
    InscriptionAction::class => fn($c) =>
        new InscriptionAction($c->get(\charlymatloc\core\application\usecases\RegisterUserService::class)),

    // 📅 RÉSERVATION
    PDOReservationRepositoryInterface::class => fn($c) =>
        new PDOReservationRepository($c->get('charlyoutils_db')),

    ReservationServiceInterface::class => fn($c) =>
        new ReservationService($c->get(PDOReservationRepositoryInterface::class)),

    // Alias DB
    'db' => static fn($c): PDO => $c->get('charlyoutils_db'),
];
