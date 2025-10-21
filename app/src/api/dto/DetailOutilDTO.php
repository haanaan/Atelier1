<?
namespace charlymatloc\api\dto;

class DetailOutilDto
{
    public string $nom;
    public string $description;
    public string $image;
    public string $categorie;
    public float $montant;

    public function __construct(string $nom, string $description, string $image, string $categorie, float $montant)
    {
        $this->nom = $nom;
        $this->description = $description;
        $this->image = $image;
        $this->categorie = $categorie;
        $this->montant = $montant;
    }
}