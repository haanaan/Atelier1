<?php
declare(strict_types=1);

use charlymatloc\core\application\entities\Outils;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use charlymatloc\api\actions\ListerOutilsAction;
use charlymatloc\api\actions\GetOutilsAction;

return function (\Slim\App $app): \Slim\App {

    $app->get(
        '/',
        fn($req, $res) =>
        $res->getBody()->write('Hello!') ? $res : $res
    );

    $app->get('/outils', ListerOutilsAction::class);
    $app->get('/outils/{id}', GetOutilsAction::class);



    return $app;
};