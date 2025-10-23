<?php

namespace charlymatloc\api\dto;

class CategorieDTO
{
    public string $id;
    public string $nom;
    public string $description;

    public function __construct(string $id, string $nom, string $description)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
    }
}
