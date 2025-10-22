<?php
namespace charlymatloc\infrastructure\repositories;

use charlymatloc\core\application\ports\UserRepository;
use charlymatloc\core\domain\entities\Utilisateurs;
use Exception;

class PDOUtilisateursRepository implements UserRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByEmail(string $email): ?Utilisateurs
    {
        try {
            $sql = "SELECT id, nom, prenom, email, password
                    FROM utilisateurs
                    WHERE LOWER(email) = :email";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':email' => strtolower($email)]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$row) {
                return null;
            }

            return new Utilisateurs(
                $row['id'],
                $row['nom'],
                $row['prenom'],
                $row['email'],
                $row['password']
            );
        } catch (Exception $e) {
            throw new Exception("Error fetching user by email: " . $e->getMessage());
        }
    }

    public function create(Utilisateurs $u): Utilisateurs
    {
        try {
            $sql = 'INSERT INTO utilisateurs (id, nom, prenom, email, password) VALUES (:id, :nom, :prenom, :email, :pwd)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id' => $u->getId(),
                ':nom' => $u->getNom(),
                ':prenom' => $u->getPrenom(),
                ':email' => $u->getEmail(),
                ':pwd' => $u->getPassword()
            ]);
            return $u;
        } catch (\PDOException $e) {
            if ($e->getCode() === '23505') {
                throw new \DomainException('Email deja utilise');
            }
            throw new Exception("Error creating user: " . $e->getMessage());
        }
    }
}
