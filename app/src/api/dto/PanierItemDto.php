<?php

namespace charlymatloc\api\dto;

class PanierItemDto
{
    public string $id_outil;
    public string $nom;  
    public float $montant;

    public function __construct(
        string $id_outil,
        string $nom,
        float $montant,  
    ) {
        $this->id_outil =$id_outil;
        $this->nom =$nom;
        $this->montant =$montant;
    }
}
