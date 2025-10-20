<?php
namespace charlymatloc\core\application\entities;


class Panier {
    private string $id;
    private string $montant;

    public function getId():string
    {
        return $this->id;
    }
    public function getMontant():string
    {
        return $this->montant;
    }
}