<?php
declare(strict_types=1);
use charlymatloc\api\actions\InscriptionAction;
use charlymatloc\api\actions\ListerOutilsAction;
use charlymatloc\api\actions\GetOutilsAction;
use charlymatloc\api\actions\GetPanierAction;

return function (\Slim\App $app): \Slim\App {
    $app->get('/api/outils', ListerOutilsAction::class);
    $app->get('/api/outils/{id}', GetOutilsAction::class);
    $app->get('/api/panier/{id}', GetPanierAction::class);
    $app->post('/api/inscription', InscriptionAction::class);

    // $app->post('/auth/signin', SigninAction::class);
    return $app;
};

