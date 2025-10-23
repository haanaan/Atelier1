<?php
namespace charlymatloc\infra\repositories;

use charlymatloc\api\dto\PanierItemDto;
use PDO;
use PDOException;
use Exception;
use charlymatloc\core\application\ports\spi\repositoryInterfaces\PanierRepositoryInterface;
use charlymatloc\core\domain\entities\Panier;
use charlymatloc\core\domain\entities\Outils;
use charlymatloc\core\domain\entities\Categorie;
use charlymatloc\core\domain\entities\Utilisateurs;
use charlymatloc\api\dto\PanierDTO;

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
                $utilisateurData['password'],
                $utilisateurData['role']
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
                $outils,
            );

            return $panier;

        } catch (PDOException $e) {
            throw new Exception("Database error in FindById: " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("Error in FindById: " . $e->getMessage());
        }
    }
    public function getOrCreatePanier(string $userId): PanierDTO
{
    try {
        // Démarrer une transaction
        $this->pdo->beginTransaction();
        
        $stmt = $this->pdo->prepare("SELECT * FROM panier WHERE id_utilisateur = :id_utilisateur");
        $stmt->execute(['id_utilisateur' => $userId]);
        $panierData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$panierData) {
            $panierId = \Ramsey\Uuid\Uuid::uuid4()->toString();
            
            $stmt = $this->pdo->prepare("INSERT INTO panier (id, id_utilisateur) VALUES (:id, :id_utilisateur)");
            $result = $stmt->execute([
                'id' => $panierId,
                'id_utilisateur' => $userId
            ]);
            
            if (!$result) {
                throw new \Exception("Échec de l'insertion du panier");
            }
            
            $itemsDTO = new PanierDTO($panierId, $userId, [], 0);
        } else {
            $panierId = $panierData['id'];
            $itemsDTO = $this->getItems($panierId);
        }
        
        $this->pdo->commit();
        
        return $itemsDTO;

    } catch (\Exception $e) {
        $this->pdo->rollBack();
        throw new \Exception("Erreur dans getOrCreatePanier: " . $e->getMessage());
    }
}
    public function getByUser(string $userId): PanierDTO
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM panier WHERE id_utilisateur = :id_utilisateur");
            $stmt->execute(['id_utilisateur' => $userId]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                throw new Exception("Aucun panier trouvé pour cet utilisateur");
            }

            return $this->getItems($data['id']);

        } catch (PDOException $e) {
            throw new Exception("Erreur base de données dans getByUser: " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("Erreur dans getByUser: " . $e->getMessage());
        }
    }

    public function getItems(string $panierId): PanierDTO
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT o.id AS outil_id, o.nom, o.montant
                FROM panier_outils i
                JOIN outils o ON o.id = i.outil_id
                WHERE i.panier_id = :id
            ");
            $stmt->execute(['id' => $panierId]);
            $itemsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $items = [];
            $total = 0;
            foreach ($itemsData as $item) {
                $items[] = new PanierItemDto(
                    $item['outil_id'],
                    $item['nom'],
                    $item['montant']
                );
                $total += $item['montant'];
            }

            $stmt = $this->pdo->prepare("SELECT id_utilisateur FROM panier WHERE id = :id");
            $stmt->execute(['id' => $panierId]);
            $panier = $stmt->fetch(PDO::FETCH_ASSOC);

            return new PanierDTO($panierId, $panier['id_utilisateur'], $items, $total);

        } catch (PDOException $e) {
            throw new Exception("Erreur base de données dans getItems: " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("Erreur dans getItems: " . $e->getMessage());
        }
    }

   public function addItem(string $panierId, string $outilId): bool
{
    try {
        $this->pdo->beginTransaction();
        
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM panier_outils 
            WHERE panier_id = :panier_id AND outil_id = :outil_id
        ");
        $stmt->execute([
            'panier_id' => $panierId, 
            'outil_id' => $outilId
        ]);

        if ($stmt->fetchColumn() > 0) {
            $this->pdo->commit();
            return true;
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO panier_outils (panier_id, outil_id)
            VALUES (:panier_id, :outil_id)
        ");
        
        $result = $stmt->execute([
            'panier_id' => $panierId, 
            'outil_id' => $outilId
        ]);

        if (!$result) {
            throw new \Exception("Échec de l'insertion de l'outil dans le panier");
        }

        if ($stmt->rowCount() === 0) {
            throw new \Exception("Aucune ligne insérée");
        }
        
        $this->pdo->commit();
        return true;
        
    } catch (\PDOException $e) {
        $this->pdo->rollBack();
        throw new \Exception("Erreur base de données dans addItem: " . $e->getMessage());
    } catch (\Exception $e) {
        $this->pdo->rollBack();
        throw $e;
    }
}

    public function removeItem(string $panierId, string $outilId): PanierDTO
    {
        try {
            $stmt = $this->pdo->prepare("
                DELETE FROM panier_outils 
                WHERE panier_id = :panier_id AND outil_id = :outil_id
            ");
            $stmt->execute(['panier_id' => $panierId, 'outil_id' => $outilId]);

            return $this->getItems($panierId);

        } catch (PDOException $e) {
            throw new Exception("Erreur base de données dans removeItem: " . $e->getMessage());
        }
    }

    public function clearItems(string $panierId): bool
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM panier_outils WHERE panier_id = :panier_id");
            return $stmt->execute(['panier_id' => $panierId]);
        } catch (PDOException $e) {
            throw new Exception("Erreur base de données dans clearItems: " . $e->getMessage());
        }
    }
}