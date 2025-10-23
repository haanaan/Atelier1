<?php

namespace charlymatloc\api\actions;

use charlymatloc\core\application\ports\api\CategorieServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetAllCategorieAction
{
    private CategorieServiceInterface $categorieService;

    public function __construct(CategorieServiceInterface $categorieService)
    {
        $this->categorieService = $categorieService;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $categories = $this->categorieService->getAllCategories();

        $response->getBody()->write(json_encode($categories));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
