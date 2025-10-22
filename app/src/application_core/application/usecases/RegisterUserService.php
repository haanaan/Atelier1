<?php
namespace charlymatloc\core\application\usecases;

use charlymatloc\core\application\ports\UserRepository;
use charlymatloc\api\dto\InscriptionDTO;
use charlymatloc\api\dto\AuthDto;
use charlymatloc\core\domain\entities\Utilisateurs;
use charlymatloc\infrastructure\repositories\PDOUtilisateurRepository;
use charlymatloc\infrastructure\repositories\PDOUtilisateursRepository;
use Ramsey\Uuid\Uuid;
use charlymatloc\core\application\usecases\TokenService;

class RegisterUserService
{
    private PDOUtilisateursRepository $repo;
    private TokenService $tokenService;

    public function __construct(PDOUtilisateursRepository $repo, TokenService $tokenService)
    {
        $this->repo = $repo;
        $this->tokenService = $tokenService;
    }

    /**
     * @param InscriptionDTO $in
     * @return AuthDto
     * @throws \InvalidArgumentException|\DomainException
     */
    public function handle(InscriptionDTO $in): AuthDto
    {
        if (!filter_var($in->email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Email invalide.');
        }
        $email = strtolower(trim($in->email));
        if ($this->repo->findByEmail($email)) {
            throw new \DomainException('Email deja utilise.');
        }
        if (strlen($in->motDePasse) < 8) {
            throw new \InvalidArgumentException('Le mot de passe doit contenir au moins 8 caractères.');
        }
        $hash = password_hash($in->motDePasse, PASSWORD_ARGON2ID);
        $id = Uuid::uuid4()->toString();
        $user = new Utilisateurs(
            $id,
            $in->nom,
            $in->prenom,
            $email,
            $hash
        );
        $savedUser = $this->repo->create($user);

        $token = null;
        if ($this->tokenService) {
            $token = $this->tokenService->generateFor($savedUser);
        }

        return AuthDto::fromUser($savedUser, $token);
    }
}