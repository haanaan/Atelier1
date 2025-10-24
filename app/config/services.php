<?php

use charlymatloc\api\actions\AddOutilToPanierAction;
use charlymatloc\api\actions\AjouterReservationAction;
use charlymatloc\api\actions\ClearPanierAction;
use charlymatloc\api\actions\GetOutilsAction;
use charlymatloc\api\actions\GetPanierAction;
use charlymatloc\api\actions\GetPanierByUserAction;
use charlymatloc\api\actions\GetUserReservationsAction;
use charlymatloc\api\actions\ListerOutilsAction;
use charlymatloc\api\actions\InscriptionAction;
use charlymatloc\api\actions\RemoveOutilFromPanierAction;
use charlymatloc\api\actions\SigninAction;
use charlymatloc\api\middlewares\AuthnMiddleware;
use charlymatloc\api\middlewares\AuthzUtilisateurMiddleware;
use charlymatloc\core\application\ports\api\OutilsServiceInterface;
use charlymatloc\core\application\ports\api\PanierServiceInterface;
use charlymatloc\core\application\ports\api\ReservationServiceInterface;
use charlymatloc\core\application\ports\api\UserServiceInterface;
use charlymatloc\core\application\ports\api\AuthnServiceInterface;
use charlymatloc\core\application\ports\spi\repositoryInterfaces\PanierRepositoryInterface;
use charlymatloc\core\application\ports\spi\repositoryinterfaces\PDOOutilsRepositoryInterface;
use charlymatloc\core\application\ports\spi\repositoryinterfaces\PDOReservationRepositoryInterface;
use charlymatloc\core\application\ports\spi\repositoryInterfaces\UserRepositoryInterface;
use charlymatloc\core\application\usecases\OutilsService;
use charlymatloc\core\application\usecases\PanierService;
use charlymatloc\core\application\usecases\ReservationService;
use charlymatloc\core\application\usecases\UserService;
use charlymatloc\core\application\usecases\AuthnService;
use charlymatloc\infra\repositories\PDOOutilsRepository;
use charlymatloc\infra\repositories\PDOPanierRepository;
use charlymatloc\infra\repositories\PDOReservationRepository;
use charlymatloc\infra\repositories\PDOUtilisateursRepository;
use charlymatloc\api\provider\jwt\JwtManager;
use charlymatloc\api\provider\AuthProviderInterface;
use charlymatloc\api\provider\jwt\JwtAuthProvider;
use charlymatloc\core\application\ports\api\AuthzUtilisateurServiceInterface;
use charlymatloc\core\application\ports\api\InscriptionServiceInterface;
use charlymatloc\core\application\usecases\AuthzUtilisateurService;
use charlymatloc\core\application\usecases\InscriptionService;
use charlymatloc\infra\repositories\UserRepository;
use charlymatloc\api\provider\jwt\JwtManagerInterface;
use charlymatloc\core\application\ports\api\CategorieServiceInterface;
use charlymatloc\core\application\usecases\CategorieService;
use charlymatloc\core\application\ports\spi\repositoryinterfaces\PDOCategorieRepositoryInterface;
use charlymatloc\infra\repositories\PDOCategorieRepository;


return [
    // PDO connection for databases
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

        // Repositories
    PDOOutilsRepositoryInterface::class => function ($c) {
        return new PDOOutilsRepository($c->get('charlyoutils_db'));
    },

    UserRepositoryInterface::class => function ($c) {
        return new UserRepository($c->get('charlyoutils_db'));
    },

    PDOCategorieRepositoryInterface::class => function ($c) {
        return new PDOCategorieRepository($c->get('charlyoutils_db'));
    },

        // Services
    OutilsServiceInterface::class => function ($c) {
        return new OutilsService($c->get(PDOOutilsRepositoryInterface::class));
    },

    CategorieServiceInterface::class => function ($c) {
        return new CategorieService($c->get(PDOCategorieRepositoryInterface::class));
    },

        // UserServiceInterface::class => function ($c) {
        //     return new UserService($c->get(UserRepositoryInterface::class), $c->get(JwtManagerInterface::class));
        // },




        // Actions
     AjouterReservationAction::class => function ($c) {
        return new AjouterReservationAction(
            $c->get(ReservationServiceInterface::class)
        );
    },
    
    GetOutilsAction::class => function ($c) {
        return new GetOutilsAction($c->get(OutilsServiceInterface::class));
    },
        GetUserReservationsAction::class => function ($c) {
        return new GetUserReservationsAction($c->get(ReservationServiceInterface::class));
    },

    GetOutilsAction::class => fn($c) =>
        new GetOutilsAction($c->get(OutilsServiceInterface::class)),


        // InscriptionAction::class => function ($c) {
        //     return new InscriptionAction($c->get(UserServiceInterface::class));
        // },

        // Panier Services
    PanierRepositoryInterface::class => function ($c) {
        return new PDOPanierRepository($c->get('charlyoutils_db'));
    },

    PanierServiceInterface::class => function ($c) {
        return new PanierService($c->get(PanierRepositoryInterface::class));
    },

    GetPanierAction::class => function ($c) {
        return new GetPanierAction($c->get(PanierServiceInterface::class));
    },

        // Inscription Service
    InscriptionServiceInterface::class => function ($c) {
        return new InscriptionService($c->get(UserRepositoryInterface::class));
    },

        // Inscription Action
    InscriptionAction::class => function ($c) {
        return new InscriptionAction($c->get(InscriptionServiceInterface::class));
    },

    PDOReservationRepositoryInterface::class => fn($c) =>
        new PDOReservationRepository($c->get('charlyoutils_db')),

    ReservationServiceInterface::class => fn($c) =>
        new ReservationService($c->get(PDOReservationRepositoryInterface::class)),

         // Auth Services
    JwtManagerInterface::class => function ($c) {
        $settings = $c->get('settings');
        $secret = $_ENV['JWT_SECRET'] ?? $settings['jwtSecret'] ?? '511e532e2b5b5842';
        $issuer = $_ENV['JWT_ISSUER'] ?? 'charlymatloc-api';
        
        $jwtManager = new JwtManager($secret);
        $jwtManager->setIssuer($issuer);
        
        return $jwtManager;
    },

    AuthNServiceInterface::class => function ($c) {
        return new AuthnService($c->get(UserRepositoryInterface::class));
    },

    AuthProviderInterface::class => function ($c) {
        return new JwtAuthProvider(
            $c->get(AuthNServiceInterface::class),
            $c->get(JwtManagerInterface::class)
        );
    },

    AuthzUtilisateurServiceInterface::class => function ($c) {
        return new AuthzUtilisateurService();
    },

    // Middleware
    AuthnMiddleware::class => function ($c) {
        return new AuthnMiddleware($c->get(AuthProviderInterface::class));
    },

    AuthzUtilisateurMiddleware::class => function ($c) {
        return new AuthzUtilisateurMiddleware($c->get(AuthzUtilisateurServiceInterface::class));
    },
        // PDOReservationRepositoryInterface::class => fn($c) =>
        //     new PDOReservationRepository($c->get('charlyoutils_db')),

    GetPanierByUserAction::class => function ($c) {
        return new GetPanierByUserAction($c->get(PanierServiceInterface::class));
    },
    AddOutilToPanierAction::class => function ($c) {
        return new AddOutilToPanierAction($c->get(PanierServiceInterface::class));
    },

    RemoveOutilFromPanierAction::class => function ($c) {
        return new RemoveOutilFromPanierAction($c->get(PanierServiceInterface::class));
    },

    ClearPanierAction::class => function ($c) {
        return new ClearPanierAction($c->get(PanierServiceInterface::class));
    },

    ReservationServiceInterface::class => fn($c) =>
        new ReservationService($c->get(PDOReservationRepositoryInterface::class)),

];
// ReservationServiceInterface::class => fn($c) =>
//     new ReservationService($c->get(PDOReservationRepositoryInterface::class)),
