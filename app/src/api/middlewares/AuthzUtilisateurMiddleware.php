<?php
namespace charlymatloc\api\middlewares;

use charlymatloc\core\application\ports\api\AuthzUtilisateurServiceInterface;
use Exception;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

class AuthzUtilisateurMiddleware
{
    private AuthzUtilisateurServiceInterface $authzService;

    public function __construct(AuthzUtilisateurServiceInterface $authzService)
    {
        $this->authzService = $authzService;
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $authDto = $request->getAttribute('authenticated_user');
            if (!$authDto) {
                throw new Exception("Erreur authentification: Authentification requise");
            }

            $routeContext = RouteContext::fromRequest($request);
            $route = $routeContext->getRoute();
            $id = $route->getArgument('id') ?? '';

            $operation = $this->getOperationFromMethod($request->getMethod());

            $this->authzService->isGranted($authDto->id, $authDto->role, $id, $operation);

            return $handler->handle($request);

        } catch (Exception $e) {
            $status = (strpos($e->getMessage(), "Erreur autorisation") === 0) ? 403 : 401;

            $response = new Response();
            $response->getBody()->write(json_encode([
                'type' => 'error',
                'error' => $status,
                'message' => $e->getMessage()
            ]));

            return $response
                ->withStatus($status)
                ->withHeader('Content-Type', 'application/json');
        }
    }

    private function getOperationFromMethod(string $method): int
    {
        switch ($method) {
            case 'GET':
                return $this->authzService->OPERATION_READ;
            case 'POST':
                return $this->authzService->OPERATION_CREATE;
            case 'PUT':
            case 'PATCH':
                return $this->authzService->OPERATION_UPDATE;
            case 'DELETE':
                return $this->authzService->OPERATION_DELETE;
            default:
                return $this->authzService->OPERATION_READ;
        }
    }
}