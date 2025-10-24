<?php
namespace charlymatloc\infra\repositories;

use PDO;
use Ramsey\Uuid\Uuid;
use charlymatloc\core\application\ports\spi\repositoryInterfaces\UserRepositoryInterface;
use charlymatloc\core\domain\entities\Utilisateurs;

class UserRepository implements UserRepositoryInterface {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function FindByEmail(string $email): ?Utilisateurs {
        $stmt = $this->pdo->prepare("SELECT id, nom, prenom, email, password, role FROM utilisateurs WHERE email = :email");
        $stmt->execute([':email' => strtolower($email)]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null;
        }
        
        return new Utilisateurs(
            $row['id'],
            $row['nom'],
            $row['prenom'],
            $row['email'],
            $row['password'],
            (int)$row['role']
        );
    }

    public function save(Utilisateurs $user): Utilisateurs {
        $id = Uuid::uuid4()->toString();

        $stmt = $this->pdo->prepare("INSERT INTO utilisateurs (id, email, password, nom, prenom,role) VALUES (:id, :email, :password, :nom, :prenom,:role)");
        $stmt->execute([
            ':id' => $id,
            ':email' => $user->getEmail(),
            ':password' => $user->getPassword(),
            ':nom' => $user->getNom(),
            ':prenom' => $user->getPrenom(),
            ':role' => $user->getRole()
        ]);
        
        return new Utilisateurs($id, $user->getNom(), $user->getPrenom(), $user->getEmail(), $user->getPassword(),$user->getRole());
    }
}
