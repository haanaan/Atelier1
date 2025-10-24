<?php
namespace charlymatloc\api\actions;

use charlymatloc\core\application\usecases\PanierService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RemoveOutilFromPanierAction {
    private PanierService $panierService;

    public function __construct(PanierService $panierService) {
        $this->panierService = $panierService;
    }

    public function __invoke(Request $request, Response $response, array $args): Response {
        try {
            $userId = $args['userId'] ?? null;
            $outilId = $args['outilId'] ?? null;

            if (!$userId || !$outilId) {
                $response->getBody()->write(json_encode([
                    'error' => 'userId et outilId sont requis.'
                ]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }

            $panierDTO = $this->panierService->removeOutilFromPanier($userId, $outilId);

            $response->getBody()->write(json_encode([
                'message' => 'Outil supprimé du panier avec succès',
                'panier' => $panierDTO
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => $e->getMessage()
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
