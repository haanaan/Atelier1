<?php

namespace charlymatloc\infra\repositories;

use charlymatloc\core\application\ports\spi\repositoryinterfaces\PDOCategorieRepositoryInterface;
use charlymatloc\core\domain\entities\Categorie;
use PDO;

class PDOCategorieRepository implements PDOCategorieRepositoryInterface
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT id, nom, description FROM categorie");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($row) =>
            new Categorie($row['id'], $row['nom'], $row['description']),
            $rows
        );
    }

    public function findById(string $id): ?Categorie
    {
        $stmt = $this->db->prepare("SELECT * FROM categorie WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Categorie($row['id'], $row['nom'], $row['description']) : null;
    }
}
