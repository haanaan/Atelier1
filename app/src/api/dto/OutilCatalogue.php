<?php
namespace src\api\dto;

class OutilCatalogue{
    public string $nom;
    public string $image;
    public int $nombreExemplaires;

    public function __construct(string $nom, string $image, int $nombreExemplaires) {
        $this->nom = $nom;
        $this->image = $image;
        $this->nombreExemplaires = $nombreExemplaires;
    }

}