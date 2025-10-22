<?php

namespace charlymatloc\api\dto;

class CredentialsDTO
{
    public string $email;
    public string $password;
    public string $nom;
    public string $prenom;

    public function __construct(string $email, string $password, string $nom, string $prenom)
    {
        $this->email = $email;
        $this->password = $password;
        $this->nom = $nom;
        $this->prenom = $prenom;
    }
}
