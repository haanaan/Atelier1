<?php
namespace charlymatloc\core\application\ports\spi\repositoryInterfaces;

use charlymatloc\core\domain\entities\Utilisateurs;

interface UserRepositoryInterface {
    public function FindByEmail(string $email): ?Utilisateurs;
    public function save(Utilisateurs $user): Utilisateurs;
}
