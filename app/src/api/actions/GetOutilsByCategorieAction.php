<?php
declare(strict_types=1);
namespace charlymatloc\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use charlymatloc\core\application\usecases\OutilsService;

class GetOutilsByCategorieAction
{
    private OutilsService $service;

    public function __construct(OutilsService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $categorieId = $args['id'];
        $outils = $this->service->AfficheOutilsByCategorie($categorieId);

        $response->getBody()->write(json_encode($outils));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
