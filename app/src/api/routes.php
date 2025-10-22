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
<<<<<<< HEAD
    $app->post('/api/inscription', InscriptionAction::class);


=======
>>>>>>> ab311b01e286380c940dd736a7be80bdd4fac34e
    return $app;
};

