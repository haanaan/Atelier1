<?php
namespace charlymatloc\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use charlymatloc\core\application\ports\api\ReservationServiceInterface;
use charlymatloc\core\domain\entities\Reservation;
use charlymatloc\core\domain\entities\Utilisateurs;
use charlymatloc\api\dto\UserProfileDTO;
use Ramsey\Uuid\Uuid;

class AjouterReservationAction
{
    private ReservationServiceInterface $service;

    public function __construct(
        ReservationServiceInterface $service
    ) {
        $this->service = $service;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $userId = $args['id'];
        $requestBody = $request->getBody()->getContents();
        $data = json_decode($requestBody, true);
        
        // autentification de l'utilisateur
        $authenticatedUser = $request->getAttribute('authenticated_user');
        
        // verifier si l'utilisateur authentifié a le droit de créer une réservation pour cet utilisateur
        if ($authenticatedUser instanceof UserProfileDTO && 
            $authenticatedUser->id !== $userId && 
            $authenticatedUser->role !== '100') { //100 est le rôle admin
            
            $response->getBody()->write(json_encode([
                'error' => 'Forbidden',
                'message' => 'You do not have permission to create a reservation for another user'
            ]));
            return $response->withStatus(403)->withHeader('Content-Type', 'application/json');
        }
        
        // creer un utilisateur minimal avec juste l'ID
        $utilisateur = new Utilisateurs(
            $userId,
            '', 
            '', 
            '', 
            '',
            1  
        );
        
        // outils de la requête
        $outilsIds = isset($data['outils']) ? $data['outils'] : [];
        
        // si outilsIds est une chaîne, la convertir en tableau
        if (is_string($outilsIds) && !empty($outilsIds)) {
            $outilsIds = explode(',', $outilsIds);
        }
        
        // génération de la reservation id
        $reservationId = $data['id'] ?? Uuid::uuid4()->toString();
        
        $currentDateTime = date('Y-m-d H:i:s');
        $dateDebut = !empty($data['datedebut']) ? $data['datedebut'] : $currentDateTime;
        $dateFin = !empty($data['datefin']) ? $data['datefin'] : date('Y-m-d H:i:s', strtotime('+7 days'));
        
        $reservation = new Reservation(
            $reservationId,
            $dateDebut,
            $dateFin,
            (float)($data['montanttotal'] ?? 0),
            $data['statut'] ?? 'pending',
            $utilisateur,
            $outilsString
        );
        
        try {
            $this->service->AjouterReservation($reservation);
                        
            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => 'Réservation ajoutée avec succès',
                'reservation_id' => $reservationId,
                'outils' => $outilsString,
                'data' => $data
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Error creating reservation',
                'message' => $e->getMessage(),
                'outils_data' => $outilsString
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
}