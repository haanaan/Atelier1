<?php
namespace charlymatloc\core\application\ports;

use charlymatloc\core\domain\entities\Utilisateurs;

interface UserRepository
{
    public function findByEmail(string $email): ?Utilisateurs;
    public function create(Utilisateurs $user): Utilisateurs;
}
