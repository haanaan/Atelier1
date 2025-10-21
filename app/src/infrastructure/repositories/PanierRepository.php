<?php
namespace charlymatloc\core\infrastructure\repositories;

use PDO;
use charlymatloc\core\application\entities\Panier;
use charlymatloc\core\application\entities\Outils;
use charlymatloc\core\application\ports\spi\repositoryInterfaces\PanierRepositoryInterface;

class PanierRepository implements PanierRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById(string $id): ?Panier
    {
        $sql = "SELECT * FROM panier WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $panierData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$panierData)
            return null;

        $sql = "SELECT o.* 
                FROM outils o
                JOIN panier_outils po ON po.outil_id = o.id
                WHERE po.panier_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $outilsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $outils = [];
        foreach ($outilsData as $o) {
            $outil = new Outils();
            foreach ($o as $key => $value) {
                $prop = strtolower($key);
                if (property_exists($outil, $prop)) {
                    $outil->$prop = $value;
                }
            }
            $outils[] = $outil;
        }

        //$panier = new Panier();
        //$panier->id = $panierData['id'];
        //$panier->outils = $outils;

        return $panier;
    }
}
