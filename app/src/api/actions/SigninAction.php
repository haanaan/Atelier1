<?php
namespace charlymatloc\api\actions;

use charlymatloc\api\dto\CredentialsDTO;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use charlymatloc\api\provider\AuthProviderInterface;

class SigninAction {
    private AuthProviderInterface $authProvider;

    public function __construct(AuthProviderInterface $authProvider) {
        $this->authProvider = $authProvider;
    }

    public function __invoke(
        ServerRequestInterface $request, 
        ResponseInterface $response
    ): ResponseInterface {
        
       $data = json_decode($request->getBody()->getContents(), true);
        
        if (!isset($data['email']) || !isset($data['password'])) {
            $error = [
                'type' => 'error',
                'error' => 400,
                'message' => 'Email and password are required'
            ];
            $response->getBody()->write(json_encode($error));
            return $response
                ->withStatus(400)
                ->withHeader('Content-Type', 'application/json');
        }
        
        $credentials = new CredentialsDTO($data['email'], $data['password']);
        
        try {
            $authDTO = $this->authProvider->signin($credentials);
            
            $result = [
                'type' => 'success',
                'user' => [
                    'id' => $authDTO->ID,
                    'email' => $authDTO->email,
                    'role' => $authDTO->role
                ],
                'access_token' => $authDTO->access_token,
                'refresh_token' => $authDTO->refresh_token
            ];
            
            $response->getBody()->write(json_encode($result));
            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
                
        } catch (\Exception $e) {
            $error = [
                'type' => 'error',
                'error' => 401,
                'message' => $e->getMessage()
            ];
            $response->getBody()->write(json_encode($error));
            return $response
                ->withStatus(401)
                ->withHeader('Content-Type', 'application/json');
        }
    }
}