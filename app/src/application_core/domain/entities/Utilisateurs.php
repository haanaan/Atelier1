<?php
namespace charlymatloc\core\application\entities;


class Utilisateurs {
    private string $id;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $password;

    public function getId():string
    {
        return $this->id;
    }
    public function getNom():string
    {
        return $this->nom;
    }
    public function getPrenom():string
    {
        return $this->prenom;
    }
    public function getEmail():string
    {
        return $this->email;
    }
    public function getPassword():string
    {
        return $this->password;
    }
}