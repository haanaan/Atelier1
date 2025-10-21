<?php
namespace charlymatloc\core\domain\entities;

class Panier {
    private string $id;
    private Utilisateurs $utilisateur;
    private array $outils;

    public function __construct(string $id, Utilisateurs $utilisateur, array $outils)
    {
        $this->id = $id;
        $this->utilisateur = $utilisateur;
        $this->outils = $outils;
    }

    public function getId(): string { return $this->id; }
    public function getUtilisateur(): Utilisateurs { return $this->utilisateur; }
    public function getOutils(): array { return $this->outils; }
}
