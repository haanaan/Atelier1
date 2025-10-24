<?php
namespace charlymatloc\api\provider;

use charlymatloc\api\dto\AuthDTO;
use charlymatloc\api\dto\CredentialsDTO;
use charlymatloc\api\dto\UserProfileDTO;

interface AuthProviderInterface {
    // public function register(CredentialsDTO $credentials, int $role): UserProfileDTO;
    
    public function signin(CredentialsDTO $credentials): AuthDTO;
    
    public function getSignedInUser(string $token): UserProfileDTO;
    
    public function refresh(string $refreshToken): AuthDTO;
}