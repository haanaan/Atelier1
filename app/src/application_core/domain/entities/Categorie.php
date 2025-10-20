<?php
namespace charlymatloc\core\application\entities;


class Categorie {
    private string $id;
    private string $nom;
    private string $description;

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
}