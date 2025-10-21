<?php
namespace charlymatloc\api\actions;

use charlymatloc\core\application\ports\spi\repositoryInterfaces\OutilsRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ListerOutilsAction
{
    private OutilsRepositoryInterface $outilsRepository;

    public function __construct(OutilsRepositoryInterface $outilsRepository)
    {
        $this->outilsRepository = $outilsRepository;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $outils = $this->outilsRepository->findAll();

        $data = [];
        foreach ($outils as $outil) {
            $data[] = [
                'id' => $outil->getId(),
                'nom' => $outil->getNom(),
                'image' => $outil->getImage(),
                'exemplaires' => 1
            ];
        }

        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
