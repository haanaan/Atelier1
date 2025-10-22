<?php

namespace charlymatloc\api\actions;

use charlymatloc\core\application\usecases\RegisterUserService;
use charlymatloc\api\dto\InscriptionDTO;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class InscriptionAction
{
    public function __construct(private RegisterUserService $usecase)
    {
    }

    public function __invoke(Request $req, Response $res): Response
    {
        $data = json_decode((string) $req->getBody(), true) ?? [];

        $dto = new InscriptionDTO(
            $data['nom'] ?? '',
            $data['prenom'] ?? '',
            $data['email'] ?? '',
            $data['motDePasse'] ?? $data['password'] ?? ''
        );

        try {
            $authDto = $this->usecase->handle($dto);

            return $this->json($res, [
                'user' => $authDto->toArray(),
            ]);

        } catch (\InvalidArgumentException $e) {
            return $this->json($res->withStatus(400), ['error' => $e->getMessage()]);
        } catch (\DomainException $e) {
            return $this->json($res->withStatus(409), ['error' => $e->getMessage()]);
        } catch (\Throwable $e) {
            return $this->json($res->withStatus(500), ['error' => 'Erreur serveur: ' . $e->getMessage()]);
        }
    }

    private function json(Response $res, array $payload): Response
    {
        $res->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        return $res->withHeader('Content-Type', 'application/json');
    }
}
