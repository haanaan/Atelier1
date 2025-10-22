<?php

namespace charlymatloc\api\dto;
class InscriptionDTO
{
    public string $nom;
    public string $prenom;
    public string $email;
    public string $motDePasse;

    public function __construct(string $nom, string $prenom, string $email, string $motDePasse)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->motDePasse = $motDePasse;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['nom'] ?? '',
            $data['prenom'] ?? '',
            $data['email'] ?? '',
            $data['motDePasse'] ?? ''
        );
    }
}
