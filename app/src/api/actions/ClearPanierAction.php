<?php
namespace charlymatloc\api\actions;
 use charlymatloc\core\application\usecases\PanierService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ClearPanierAction{
    private PanierService $panierService;
    public function __construct(PanierService $panierService){
        $this->panierService=$panierService;
    }
    public function __invoke(Request $request,Response $response,array $args):Response{
        try {
            $userId = $args['userId'] ?? null;

            if (!$userId) {
                $response->getBody()->write(json_encode([
                    'error' => 'userId est requis.'
                ]));
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(400);
            }

            $result = $this->panierService->clearPanier($userId);

            if ($result) {
                $response->getBody()->write(json_encode([
                    'message' => 'Le panier a été vidé avec succès.'
                ]));
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(200);
            } else {
                $response->getBody()->write(json_encode([
                    'error' => 'Impossible de vider le panier.'
                ]));
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(500);
            }

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => $e->getMessage()
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }

    }
}