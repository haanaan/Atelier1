<?php
namespace charlymatloc\core\domain\entities;

class Utilisateurs {
    private string $id;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $password;

    public function __construct(string $id, string $nom, string $prenom, string $email, string $password)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password;
    }

    public function getId(): string { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getPrenom(): string { return $this->prenom; }
    public function getEmail(): string { return $this->email; }
    public function getPassword(): string { return $this->password; }
}
