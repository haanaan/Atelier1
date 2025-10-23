<?php

namespace charlymatloc\core\application\usecases;

use charlymatloc\api\dto\InscriptionDTO;
use charlymatloc\core\application\ports\api\InscriptionServiceInterface;
use charlymatloc\core\application\ports\spi\repositoryInterfaces\UserRepositoryInterface;
use charlymatloc\core\domain\entities\Utilisateurs;
use Exception;

class InscriptionService implements InscriptionServiceInterface {
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function register(InscriptionDTO $inscriptionDTO): Utilisateurs {
        $existingUser = $this->userRepository->FindByEmail($inscriptionDTO->email);
        if ($existingUser !== null) {
            throw new Exception("User with this email already exists");
        }

        $role = 1; 
        $user = Utilisateurs::createNewUser(
            $inscriptionDTO->nom,
            $inscriptionDTO->prenom,
            $inscriptionDTO->email,
            $inscriptionDTO->motDePasse,
            $role
        );

        return $this->userRepository->save($user);
    }
}
