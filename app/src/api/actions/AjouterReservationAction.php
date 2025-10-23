<?php
namespace charlymatloc\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use charlymatloc\core\application\ports\api\ReservationServiceInterface;
use charlymatloc\core\domain\entities\Reservation;
use charlymatloc\core\domain\entities\Utilisateurs;

class AjouterReservationAction
{
    private ReservationServiceInterface $service;

    public function __construct(ReservationServiceInterface $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $data = (array)$request->getParsedBody();

        $utilisateur = new Utilisateurs(
            $data['utilisateur_id'],
            $data['nom'] ?? '',
            $data['prenom'] ?? '',
            $data['email'] ?? '',
            $data['password'] ?? '',
            $data['role'],''
        );

        $reservation = new Reservation(
            $data['id'],
            $data['datedebut'],
            $data['datefin'],
            $data['montanttotal'],
            $data['statut'],
            $utilisateur,
            $data['outils'] ?? ''
        );

    $this->service->AjouterReservation($reservation);

        $response->getBody()->write(json_encode(['message' => 'Réservation ajoutée avec succès']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
}
