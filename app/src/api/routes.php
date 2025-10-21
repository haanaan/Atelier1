<?php

use Slim\App;
use charlymatloc\api\actions\ListerOutilsAction;
use charlymatloc\api\actions\GetOutilsAction;

return function (App $app) {
    // Route pour lister tous les outils
    $app->get('/api/outils', ListerOutilsAction::class);

    // Route pour obtenir un outil par son ID
    $app->get('/api/outils/{id}', GetOutilsAction::class);
};
