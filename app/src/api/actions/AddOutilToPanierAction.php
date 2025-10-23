<?php
 namespace charlymatloc\api\actions;
 use charlymatloc\core\application\usecases\PanierService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AddOutilToPanierAction{
    private PanierService $panierService;
    public function __construct(PanierService $panierService){
        $this->panierService=$panierService;

    }
    public function __invoke(Request $request,Response $response,array $args):Response{
        try {
    
        $userId = $args['userId'];
        $outilId = $args['outilId'];

        $panier = $this->panierService->addOutilToPanier($userId, $outilId);

        $response->getBody()->write(json_encode($panier));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
    }
}