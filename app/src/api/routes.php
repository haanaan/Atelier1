<?php

use Slim\App;
use charlymatloc\api\actions\ListerOutilsAction;
use charlymatloc\api\actions\GetOutilsAction;
use charlymatloc\api\actions\GetPanierAction;

return function (App $app) {
    // Route pour lister tous les outils
    $app->get('/api/outils', ListerOutilsAction::class);

    $app->get(
        '/',
        fn($req, $res) =>
        $res->getBody()->write('Hello!') ? $res : $res
    );

    $app->get('/outils', ListerOutilsAction::class);
    $app->get('/outils/{id}', GetOutilsAction::class);
    $app->get('/panier/{id}', GetPanierAction::class);



    return $app;
};
