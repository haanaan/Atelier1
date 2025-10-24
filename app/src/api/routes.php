<?php
declare(strict_types=1);
use charlymatloc\api\actions\GetReservationAction;
use charlymatloc\api\actions\ClearPanierAction;
use charlymatloc\api\actions\GetAllCategorieAction;
use charlymatloc\api\actions\GetPanierByUserAction;
use charlymatloc\api\actions\InscriptionAction;
use charlymatloc\api\actions\SigninAction;
use charlymatloc\api\actions\RefreshTokenAction;
use charlymatloc\api\actions\ListerOutilsAction;
use charlymatloc\api\actions\GetOutilsAction;
use charlymatloc\api\actions\GetPanierAction;
use charlymatloc\api\actions\AddOutilToPanierAction;
use charlymatloc\api\actions\RemoveOutilFromPanierAction;
use charlymatloc\api\actions\ListerReservationsAction;
use charlymatloc\api\actions\AjouterReservationAction;
use charlymatloc\api\actions\SupprimerReservationAction;
use charlymatloc\api\middlewares\AuthnMiddleware;
use charlymatloc\api\middlewares\AuthzUtilisateurMiddleware;

return function (\Slim\App $app): \Slim\App {
    // Routes publiques - sans authentification
    $app->get('/api/outils', ListerOutilsAction::class);
    $app->get('/api/outils/{id}', GetOutilsAction::class);
    $app->post('/api/inscription', InscriptionAction::class);
    
    $app->post('/api/signin', SigninAction::class);
    $app->post('/api/refresh-token', RefreshTokenAction::class);
    
    $app->group('/api', function (\Slim\Routing\RouteCollectorProxy $group) {


        $group->get('/panier/{id}', GetPanierAction::class)->add(AuthzUtilisateurMiddleware::class);
        
        $group->get('/reservations', ListerReservationsAction::class)->add(AuthzUtilisateurMiddleware::class);
        $group->post('/reservations', AjouterReservationAction::class)->add(AuthzUtilisateurMiddleware::class);
        $group->delete('/reservations/{id}', SupprimerReservationAction::class)->add(AuthzUtilisateurMiddleware::class);
    })->add(AuthnMiddleware::class);
$app->get('/users/{userId}/panier', GetPanierByUserAction::class);
$app->post('/users/{userId}/panier/outils/{outilId}', AddOutilToPanierAction::class);
$app->delete('/users/{userId}/panier/outils/{outilId}', RemoveOutilFromPanierAction::class);
$app->delete('/users/{userId}/panier/clear', ClearPanierAction::class);
    $app->get('/users/{userId}/panier', GetPanierByUserAction::class);
    $app->delete('/users/{userId}/panier/clear', ClearPanierAction::class);

    $app->get('/api/categories', GetAllCategorieAction::class);
    return $app;
};