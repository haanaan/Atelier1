<?php
namespace charlymatloc\core\application\usecases;

use charlymatloc\api\dto\CredentialsDTO;
use charlymatloc\api\dto\UserProfileDTO;
use Exception;
use charlymatloc\core\application\ports\api\AuthNServiceInterface;
use charlymatloc\core\domain\entities\Utilisateurs;
use charlymatloc\core\application\ports\spi\repositoryInterfaces\UserRepositoryInterface;

class AuthnService implements AuthNServiceInterface {
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;
    }

    // public function register(CredentialsDTO $credentials, int $role): UserProfileDTO {
    //     // Vérifier si l'utilisateur existe déjà
    //     $existingUser = $this->userRepository->FindByEmail($credentials->email);
    //     if ($existingUser !== null) {
    //         throw new Exception("User with this email already exists");
    //     }

    //     // Hasher le mot de passe
    //     $hashedPassword = password_hash($credentials->password, PASSWORD_DEFAULT);
        
    //     // Créer l'utilisateur
    //     // $user = new Utilisateurs(
    //     //     '',
    //     //     $credentials->email,
    //     //     $hashedPassword,
    //     //     (string)$role
    //     // );
        
    //     $savedUser = $this->userRepository->save($user);
        
    //     return new UserProfileDTO(
    //         $savedUser->getId(),
    //         $savedUser->getEmail(),
    //         $savedUser->getRole()
    //     );
    // }

    public function byCredentials(CredentialsDTO $credentials): UserProfileDTO {
        $user = $this->userRepository->FindByEmail($credentials->email);
        
        if ($user === null) {
            throw new Exception("Invalid credentials");
        }
        
        if (!$user->verifyPassword($credentials->password)) {
            throw new Exception("Invalid credentials");
        }
        
        return new UserProfileDTO(
            $user->getId(),
            $user->getEmail(),
            $user->getRole()
        );
    }
}