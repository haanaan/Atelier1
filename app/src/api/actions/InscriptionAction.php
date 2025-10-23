<?php
namespace charlymatloc\api\actions;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use charlymatloc\core\application\ports\api\InscriptionServiceInterface;
use charlymatloc\api\dto\InscriptionDTO;
use Exception;

class InscriptionAction {
    private InscriptionServiceInterface $inscriptionService;

    public function __construct(InscriptionServiceInterface $inscriptionService) {
        $this->inscriptionService = $inscriptionService;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        $data = json_decode($request->getBody()->getContents(), true);
        // error_log(print_r($data, true)); 

        // error_log(print_r($data, true)); 

        if (!isset($data['nom'], $data['prenom'], $data['email'], $data['motDePasse'])) {
            $response->getBody()->write(json_encode(['message' => 'Nom, prénom, email, and motDePasse are required']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $inscriptionDTO = new InscriptionDTO(
            $data['nom'],
            $data['prenom'],
            $data['email'],
            $data['motDePasse']
        );

        try {
            $user = $this->inscriptionService->register($inscriptionDTO);

            $response->getBody()->write(json_encode([
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'message' => 'User registered successfully'
            ]));
            return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['message' => $e->getMessage()]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }
}


