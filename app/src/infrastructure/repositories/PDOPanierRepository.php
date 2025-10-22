<?php
namespace charlymatloc\infrastructure\repositories;

use PDO;
use PDOException;
use Exception;
use charlymatloc\core\application\ports\spi\repositoryInterfaces\PanierRepositoryInterface;
use charlymatloc\core\domain\entities\Panier;
use charlymatloc\core\domain\entities\Outils;
use charlymatloc\core\domain\entities\Categorie;
use charlymatloc\core\domain\entities\Utilisateurs;

class PDOPanierRepository implements PanierRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById(string $id): ?Panier
    {
        try {
            $stmt = $this->pdo->prepare("SELECT p.id, p.id_utilisateur as utilisateur FROM panier p WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $panierData = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$panierData) {
                return null;
            }

            $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE id = :utilisateur_id");
            $stmt->execute(['utilisateur_id' => $panierData['utilisateur']]);
            $utilisateurData = $stmt->fetch(PDO::FETCH_ASSOC);

            $utilisateur = new Utilisateurs(
                $utilisateurData['id'],
                $utilisateurData['nom'],
                $utilisateurData['prenom'],
                $utilisateurData['email'],
                $utilisateurData['password']
            );

            $stmt = $this->pdo->prepare("
                SELECT o.id, o.nom, o.description, o.montant, o.image, o.exemplaires,
                       c.id AS cat_id, c.nom AS cat_nom, c.description AS cat_description
                FROM outils o
                JOIN categorie c ON o.categorie_id = c.id
                JOIN panier_outils po ON po.outil_id = o.id
                WHERE po.panier_id = :id
            ");
            $stmt->execute(['id' => $id]);
            $outilsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $outils = [];
            foreach ($outilsData as $data) {
                $outils[] = new Outils(
                    $data['id'],
                    $data['nom'],
                    $data['description'],
                    $data['montant'],
                    $data['image'],
                    $data['exemplaires'],
                    new Categorie(
                        $data['cat_id'],
                        $data['cat_nom'],
                        $data['cat_description']
                    )
                );
            }

            $panier = new Panier(
                $panierData['id'],
                $utilisateur,
                $outils
            );

            return $panier;

        } catch (PDOException $e) {
            throw new Exception("Database error in FindById: " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("Error in FindById: " . $e->getMessage());
        }
    }
}
