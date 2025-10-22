<?php
namespace charlymatloc\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use charlymatloc\core\application\ports\api\ReservationServiceInterface;

class ListerReservationsAction
{
    private ReservationServiceInterface $service;

    public function __construct(ReservationServiceInterface $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $reservations = $this->service->ListerReservations();
        $response->getBody()->write(json_encode($reservations));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
