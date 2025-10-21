<?php
namespace charlymatloc\api\actions;

use charlymatloc\core\application\ports\spi\repositoryinterfaces\PDOOutilsRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use charlymatloc\core\application\ports\api\OutilsServiceInterface;

class ListerOutilsAction
{
    private OutilsServiceInterface $outilservice;

    public function __construct(OutilsServiceInterface $outilservice)
    {
        $this->outilservice = $outilservice;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $outils = $this->outilservice->AfficheOutils();

        $response->getBody()->write(json_encode($outils));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
