<?php

namespace charlymatloc\infra\repositories;

use charlymatloc\core\domain\entities\Categorie;
use charlymatloc\core\domain\entities\Outils;
use charlymatloc\core\application\ports\spi\repositoryinterfaces\PDOOutilsRepositoryInterface;
use Exception;
class PDOOutilsRepository implements PDOOutilsRepositoryInterface
{
    private \PDO $pdo; 

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function FindAll(): array {
        try {
            $statement = $this->pdo->prepare("
                SELECT o.id, o.nom, o.description, o.montant, o.image, o.exemplaires, 
                       c.id AS cat_id, c.nom AS cat_nom, c.description AS cat_description
                FROM outils o
                JOIN categorie c ON o.categorie_id = c.id
            ");
            $statement->execute();
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
            
            $outils = [];
            foreach ($results as $res) {
                $outils[] = new Outils(
                    $res['id'],
                    $res['nom'],
                    $res['description'],
                    $res['montant'],
                    $res['image'],
                    $res['exemplaires'],
                    new Categorie(
                        $res['cat_id'],
                        $res['cat_nom'],
                        $res['cat_description']
                    )
                );
            }
            return $outils;
        } catch (Exception $e) {
            throw new Exception("Error fetching all tools: " . $e->getMessage());
        }
    }

    public function findbyId(string $id_p): Outils {
        try {
            $statement = $this->pdo->prepare("
                SELECT o.id, o.nom, o.description, o.montant, o.image, o.exemplaires, 
                       c.id AS cat_id, c.nom AS cat_nom, c.description AS cat_description
                FROM outils o
                JOIN categorie c ON o.categorie_id = c.id
                WHERE o.id = :id_p
            ");
            $statement->execute([":id_p" => $id_p]);
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            
            if (!$result) {
                throw new Exception("Outil not found");
            }

            return new Outils(
                $result['id'],
                $result['nom'],
                $result['description'],
                $result['montant'],
                $result['image'],
                $result['exemplaires'],
                new Categorie(
                    $result['cat_id'],
                    $result['cat_nom'],
                    $result['cat_description']
                )
            );
        } catch (Exception $e) {
            throw new Exception("Error fetching tool with ID $id_p: " . $e->getMessage());
        }
    }
}