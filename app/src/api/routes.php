<?php
declare(strict_types=1);
use charlymatloc\api\actions\ListerOutilsAction;
use charlymatloc\api\actions\GetOutilsAction;


return function(\Slim\App $app): \Slim\App {
    $app->get('/api/outils', ListerOutilsAction::class);

    // Route pour obtenir un outil par son ID
    $app->get('/api/outils/{id}', GetOutilsAction::class);

    return $app;
};
