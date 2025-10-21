<?php

namespace charlymatloc\api\dto;

class PanierDTO
{
    public string $id;
    public string $utilisateur;
    public array $outils;  
    public float $total;

    public function __construct(
        string $id,
        string $utilisateur,
        array $outils,  
        float $total,  
    ) {
        $this->id = $id;
        $this->utilisateur = $utilisateur;
        $this->outils = $outils;
        $this->total = $total;
    }
}
