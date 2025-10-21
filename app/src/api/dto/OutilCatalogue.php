<?php
namespace charlymatloc\api\dto;

class OutilCatalogue
{
    public string $id;
    public string $nom;
    public string $image;
    public int $nombreExemplaires;

    public function __construct(string $id, string $nom, string $image, int $nombreExemplaires)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->image = $image;
        $this->nombreExemplaires = $nombreExemplaires;
    }

}