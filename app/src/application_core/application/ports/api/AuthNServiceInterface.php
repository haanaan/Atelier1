<?php
namespace charlymatloc\core\application\ports\api;

use charlymatloc\api\dto\CredentialsDTO;
use charlymatloc\api\dto\UserProfileDTO;

interface AuthNServiceInterface {
    // public function register(CredentialsDTO $credentials, int $role): UserProfileDTO;
    public function byCredentials(CredentialsDTO $credentials): UserProfileDTO;
}