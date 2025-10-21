<?php
namespace charlymatloc\core\infrastructure\repositories;

use PDO;
use PDOException;
use charlymatloc\core\application\entities\Panier;
use charlymatloc\core\application\entities\Outils;
use charlymatloc\core\application\ports\spi\repositoryInterfaces\PanierRepositoryInterface;

class PDOPanierRepository implements PanierRepositoryInterface
{
    private PDO $pdo;

    public function __construct()
    {
        $host = getenv('DB_HOST') ?: 'charypanier.db';
        $port = getenv('DB_PORT') ?: '5432';
        $dbname = getenv('DB_NAME') ?: 'charypanier';
        $user = getenv('DB_USER') ?: 'charypanier';
        $pass = getenv('DB_PASSWORD') ?: 'charypanier';

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";

        try {
            $this->pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Connexion PDO échouée : " . $e->getMessage());
        }
    }

    public function findById(string $id): ?Panier
    {
        $stmt = $this->pdo->prepare("SELECT * FROM panier WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $panierData = $stmt->fetch();

        if (!$panierData) {
            return null;
        }

        $stmt = $this->pdo->prepare("
            SELECT o.* 
            FROM outils o
            JOIN panier_outils po ON po.outil_id = o.id
            WHERE po.panier_id = :id
        ");
        $stmt->execute(['id' => $id]);
        $outilsData = $stmt->fetchAll();

        $outils = [];
        foreach ($outilsData as $data) {
            $outil = new Outils();
            foreach ($data as $key => $value) {
                $prop = strtolower($key);
                if (property_exists($outil, $prop)) {
                    $outil->$prop = $value;
                }
            }
            $outils[] = $outil;
        }

        $panier = new Panier();
    }
}