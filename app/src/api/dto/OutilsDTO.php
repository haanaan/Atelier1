<?php

namespace charlymatloc\api\dto;

class OutilsDTO
{
    public string $id;
    public string $nom;
    public string $description;
    public float $montant;
    public string $image;
    public int $exemplaires;
    public string $categorie_nom;
    public string $categorie_description;

    public function __construct(
        string $id,
        string $nom,
        string $description,
        float $montant,
        string $image,
        int $exemplaires,
        string $categorie_nom,
        string $categorie_description
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->montant = $montant;
        $this->image = $image;
        $this->exemplaires = $exemplaires;
        $this->categorie_nom = $categorie_nom;
        $this->categorie_description = $categorie_description;
    }
}
