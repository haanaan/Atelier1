<?php
namespace charlymatloc\core\domain\entities;


class Panier {
    private string $id;
    private Utilisateurs $utilisateur;
    private array $outils;

    public function getId():string
    {
        return $this->id;
    }
    public function getUtilisateur():Utilisateurs
    {
        return $this->utilisateur;
    }
    public function getOutils(): array {
        return $this->outils;
    }
}