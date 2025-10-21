<?php

namespace charlymatloc\api\actions;

use charlymatloc\core\application\ports\api\PanierServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetPanierAction
{
    private PanierServiceInterface $panierService;

    public function __construct(PanierServiceInterface $panierService)
    {
        $this->panierService = $panierService;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        
        $result = $this->panierService->getPanierAvecTotal($id);

        if ($result === null) {
            $response->getBody()->write(json_encode(['error' => 'Panier not found']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
