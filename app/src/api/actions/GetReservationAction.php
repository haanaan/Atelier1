<?php
namespace charlymatloc\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use charlymatloc\core\application\ports\api\ReservationServiceInterface;

class GetReservationAction
{
    private ReservationServiceInterface $service;

    public function __construct(ReservationServiceInterface $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'] ?? null;
        if (!$id) {
            $response->getBody()->write(json_encode(['error' => 'ID manquant']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $reservation = $this->service->AfficheById($id);

        if ($reservation === null) {
            $response->getBody()->write(json_encode(['error' => 'Réservation introuvable']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($reservation));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
