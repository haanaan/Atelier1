<?php
namespace charlymatloc\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use charlymatloc\core\application\ports\api\ReservationServiceInterface;
use charlymatloc\api\dto\UserProfileDTO;

class GetUserReservationsAction
{
    private ReservationServiceInterface $service;

    public function __construct(ReservationServiceInterface $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $userId = $args['id'];
        
        $authenticatedUser = $request->getAttribute('authenticated_user');
        
        if ($authenticatedUser && $authenticatedUser instanceof UserProfileDTO 
            && $authenticatedUser->id !== $userId 
            && $authenticatedUser->role !== '100') { 
            
            $response->getBody()->write(json_encode([
                'error' => 'Forbidden',
                'message' => 'You do not have permission to access this resource'
            ]));
            return $response->withStatus(403)->withHeader('Content-Type', 'application/json');
        }
        
        $reservations = $this->service->ListerReservationsByUserId($userId);
        
        $response->getBody()->write(json_encode($reservations));
        return $response->withHeader('Content-Type', 'application/json');
    }
}