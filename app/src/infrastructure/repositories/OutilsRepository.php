<?php
namespace charlymatloc\core\infrastructure\repositories;

use charlymatloc\core\application\entities\Outils;
use charlymatloc\core\application\ports\spi\repositoryInterfaces\OutilsRepositoryInterface;
use PDO;

class OutilsRepository implements OutilsRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById(string $id): ?Outils
    {
        $sql = "SELECT * FROM outils WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $outil = new Outils();
        // Hydratation simple :
        $ref = new \ReflectionClass(Outils::class);
        foreach ($data as $key => $value) {
            if ($ref->hasProperty($key)) {
                $prop = $ref->getProperty($key);
                $prop->setAccessible(true);
                $prop->setValue($outil, $value);
            }
        }

        return $outil;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM outils";
        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $outils = [];
        foreach ($rows as $row) {
            $outil = new Outils();
            $ref = new \ReflectionClass(Outils::class);
            foreach ($row as $key => $value) {
                if ($ref->hasProperty($key)) {
                    $prop = $ref->getProperty($key);
                    $prop->setAccessible(true);
                    $prop->setValue($outil, $value);
                }
            }
            $outils[] = $outil;
        }

        return $outils;
    }
}
