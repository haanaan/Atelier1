<?php
namespace charlymatloc\core\application\ports\api;

interface AuthzUtilisateurServiceInterface {
    public function isGranted(string $user_id, string $role, string $ressource_id, int $operation=1): bool;
}
