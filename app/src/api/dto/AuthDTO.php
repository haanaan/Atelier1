<?php

namespace charlymatloc\api\dto;

class AuthDto
{
    public string $id;
    public string $email;
    public string $nom;
    public string $prenom;
    public ?string $token;

    public function __construct(string $id, string $email, string $nom, string $prenom, ?string $token = null)
    {
        $this->id = $id;
        $this->email = $email;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->token = $token;
    }

    public static function fromUser(object $user, ?string $token = null): self
    {
        return new self(
            $user->getId(),
            $user->getEmail(),
            $user->getNom(),
            $user->getPrenom(),
            $token
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'token' => $this->token,
        ];
    }
}