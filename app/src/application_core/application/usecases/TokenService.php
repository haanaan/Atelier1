<?php

namespace charlymatloc\core\application\usecases;

use charlymatloc\core\domain\entities\Utilisateurs;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokenService
{
    private string $secret;
    private string $algo;
    private string $issuer;
    private int $ttl;

    /**
     * @param string $secret  
     * @param string $algo   
     * @param string $issuer 
     * @param int $ttl        
     */
    public function __construct(string $secret, string $algo = 'HS256', int $ttl = 3600)
    {
        $this->secret = $secret;
        $this->algo = $algo;
        $this->ttl = $ttl;
    }

    /**
     * @param Utilisateurs $user
     * @return string
     */
    public function generateFor(Utilisateurs $user): string
    {
        $now = time();
        $payload = [
            'iss' => $this->issuer,
            'iat' => $now,
            'exp' => $now + $this->ttl,
            'sub' => $user->getId(),
            'email' => $user->getEmail(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
        ];

        return JWT::encode($payload, $this->secret, $this->algo);
    }

    /**
     * @param string $token
     * @return object|null
     */
    public function verify(string $token): ?object
    {
        try {
            return JWT::decode($token, new Key($this->secret, $this->algo));
        } catch (\Exception $e) {
            return null;
        }
    }
}
