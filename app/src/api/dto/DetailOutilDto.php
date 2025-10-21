<?
namespace src\api\dto;

class DetailOutilDto{
     public string $nom;
    public string $description;
    public string $image;
    public string $categorie;

    public function __construct(string $nom, string $description, string $image, string $categorie) {
        $this->nom = $nom;
        $this->description = $description;
        $this->image = $image;
        $this->categorie = $categorie;
    }
}