<?php
declare(strict_types=1);
use charlymatloc\api\actions\InscriptionAction;
use charlymatloc\api\actions\SigninAction;
use charlymatloc\api\actions\RefreshTokenAction;
use charlymatloc\api\actions\ListerOutilsAction;
use charlymatloc\api\actions\GetOutilsAction;
use charlymatloc\api\actions\GetPanierAction;
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

        // Routes qui nécessitent uniquement l'authentification
        $group->get('/panier/{id}', GetPanierAction::class)->add(AuthzUtilisateurMiddleware::class);
        
        // Routes qui nécessitent l'authentification ET l'autorisation
        $group->get('/reservations', ListerReservationsAction::class)->add(AuthzUtilisateurMiddleware::class);
        $group->post('/reservations', AjouterReservationAction::class)->add(AuthzUtilisateurMiddleware::class);
        $group->delete('/reservations/{id}', SupprimerReservationAction::class)->add(AuthzUtilisateurMiddleware::class);
    })->add(AuthnMiddleware::class);

    return $app;
};