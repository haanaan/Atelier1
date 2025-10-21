<?php
namespace scr\api\dto;

class Inscription{
     public string $nom;
    public string $prenom;
    public string $email;
    public string $motDePasse;

    public function __construct(string $nom, string $prenom, string $email, string $motDePasse) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->motDePasse = $motDePasse;
    }
}