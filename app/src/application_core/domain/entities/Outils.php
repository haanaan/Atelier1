<?php
namespace charlymatloc\core\application\entities;


class Outils {
    private string $id;
    private string $nom;
    private string $description;
    private string $image;
    private string $exemplaires;
    private Categorie $categorie;

    public function getId():string
    {
        return $this->id;
    }
    public function getNom():string
    {
        return $this->nom;
    }
    public function getDescription():string
    {
        return $this->description;
    }
    public function getImage():string
    {
        return $this->image;
    }
    public function getExemplaires():string
    {
        return $this->exemplaires;
    }
    public function getCategorie():Categorie
    {
        return $this->categorie;
    }
}