<?php
namespace charlymatloc\core\domain\entities;


class Categorie {
    private string $id;
    private string $nom;
    private string $description;
        public function __construct(string $id,
        string $nom,
        string $description
        ){
            $this->nom = $nom;
            $this->id= $id;
            $this->description = $description;
        }

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