<?php
namespace charlymatloc\api\actions;

use charlymatloc\core\application\ports\spi\repositoryInterfaces\OutilsRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetOutilsAction
{
    private OutilsRepositoryInterface $outilsRepository;

    public function __construct(OutilsRepositoryInterface $outilsRepository)
    {
        $this->outilsRepository = $outilsRepository;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $outil = $this->outilsRepository->findById($id);

        if ($outil === null) {
            $response->getBody()->write(json_encode(['error' => 'Outil not found']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode([
            'id' => $outil->getId(),
            'nom' => $outil->getNom(),
            'description' => $outil->getDescription(),
            'image' => $outil->getImage(),
            'exemplaires' => 1
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
