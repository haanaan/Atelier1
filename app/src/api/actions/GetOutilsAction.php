<?php
namespace charlymatloc\api\actions;

use charlymatloc\core\application\ports\api\OutilsServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class GetOutilsAction
{
    private OutilsServiceInterface $outils_service;

    public function __construct(OutilsServiceInterface $outils_service)
    {
        $this->outils_service = $outils_service;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $outil = $this->outils_service->AfficheById($id);

        if ($outil === null) {
            $response->getBody()->write(json_encode(['error' => 'Outil not found']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($outil));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
