<?php
declare(strict_types=1);
use charlymatloc\api\actions\ClearPanierAction;
use charlymatloc\api\actions\GetAllCategorieAction;
use charlymatloc\api\actions\GetPanierByUserAction;
use charlymatloc\api\actions\InscriptionAction;
use charlymatloc\api\actions\ListerOutilsAction;
use charlymatloc\api\actions\GetOutilsAction;
use charlymatloc\api\actions\GetPanierAction;
<<<<<<< HEAD
use charlymatloc\api\actions\AddOutilToPanierAction;
use charlymatloc\api\actions\RemoveOutilFromPanierAction;
=======
>>>>>>> 308394c84991fccf6b19bc29bc84f093406c8a60
use charlymatloc\api\actions\ListerReservationsAction;
use charlymatloc\api\actions\AjouterReservationAction;
use charlymatloc\api\actions\SupprimerReservationAction;

return function (\Slim\App $app): \Slim\App {
    $app->get('/api/outils', ListerOutilsAction::class);
    $app->get('/api/outils/{id}', GetOutilsAction::class);
    $app->get('/api/panier/{id}', GetPanierAction::class);
    $app->post('/api/inscription', InscriptionAction::class);

<<<<<<< HEAD
$app->get('/users/{userId}/panier', GetPanierByUserAction::class);
$app->post('/users/{userId}/panier/outils/{outilId}', AddOutilToPanierAction::class);
$app->delete('/users/{userId}/panier/outils/{outilId}', RemoveOutilFromPanierAction::class);
$app->delete('/users/{userId}/panier/clear', ClearPanierAction::class);
=======
    $app->get('/users/{userId}/panier', GetPanierByUserAction::class);
    //$app->post('/users/{userId}/panier/outils/{outilId}', AddOutilToPanierAction::class);
    //$app->delete('/users/{userId}/panier/outils/{outilId}', RemoveOutilFromPanierAction::class);
    $app->delete('/users/{userId}/panier/clear', ClearPanierAction::class);
>>>>>>> 308394c84991fccf6b19bc29bc84f093406c8a60

    $app->get('/api/reservations', ListerReservationsAction::class);
    $app->post('/api/reservations', AjouterReservationAction::class);
    $app->delete('/api/reservations/{id}', SupprimerReservationAction::class);

    $app->get('/api/categories', GetAllCategorieAction::class);
    return $app;
};

