<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

return function( \Slim\App $app):\Slim\App {


    $app->get('/',function(Request $req, Response $resp){
         $resp->getBody()->write("Hello");
         return $resp;
    });

  

    return $app;
};