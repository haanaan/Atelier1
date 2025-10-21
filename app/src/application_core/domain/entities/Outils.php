<?php
namespace charlymatloc\core\domain\entities;


class Outils {
    private string $id;
    private string $nom;
    private string $description;
    private string $montant;
    private string $image;
    private string $exemplaires;
    private Categorie $categorie;

    public function __construct(
        string $id,
        string $nom,
        string $description,
        string $montant,
        string $image,
        string $exemplaires,
        Categorie $categorie
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->montant = $montant;
        $this->image = $image;
        $this->exemplaires = $exemplaires;
        $this->categorie = $categorie;
    }

    public function getId(): string { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getDescription(): string { return $this->description; }
    public function getMontant(): string { return $this->montant; }
    public function getImage(): string { return $this->image; }
    public function getExemplaires(): string { return $this->exemplaires; }
    public function getCategorie(): Categorie { return $this->categorie; }
}