<?php
namespace  charlymatloc\api\actions;
use charlymatloc\core\application\usecases\PanierService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetPanierByUserAction{
    private PanierService $panierService;
    public function __construct(PanierService $panierService){
        $this->panierService=$panierService;
    }
    public function __invoke(Request $request,Response $response,array $args){
          $userId = $args['userId'] ?? null;

        if (!$userId) {
            $response->getBody()->write(json_encode([
                'error' => 'Paramètre "userId" manquant.'
            ]));
            return $response->withStatus(400)
                            ->withHeader('Content-Type', 'application/json');
        }

        try {
            $panierDTO = $this->panierService->getPanierByUser($userId);

            $payload = [
                'id' => $panierDTO->id,
                'userId' => $panierDTO->utilisateur,
                'total' => $panierDTO->total,
                'items' => array_map(function ($item) {
                    return [
                        'id' => $item->id,
                        'nom' => $item->nom,
                        'montant' => $item->montant
                    ];
                }, $panierDTO->outils)
            ];

            $response->getBody()->write(json_encode($payload));
            return $response->withHeader('Content-Type', 'application/json');

        } catch (\Exception $e) {
            // 🔹 Gestion des erreurs
            $response->getBody()->write(json_encode([
                'error' => $e->getMessage()
            ]));
            return $response->withStatus(500)
                            ->withHeader('Content-Type', 'application/json');
        }

    }

}