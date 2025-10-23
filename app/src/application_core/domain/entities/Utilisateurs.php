<?php
namespace charlymatloc\core\domain\entities;

use Ramsey\Uuid\Uuid;

class Utilisateurs {
    private string $id;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $password;
    private string $role;

    public function __construct(string $id, string $nom, string $prenom, string $email, string $password,int $role)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public function getId(): string { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getPrenom(): string { return $this->prenom; }
    public function getEmail(): string { return $this->email; }
    public function getPassword(): string { return $this->password; }
    public function getRole(): string { return $this->role; }
     public static function createNewUser(string $nom, string $prenom, string $email, string $password, int $role): Utilisateurs
    {
        $id = Uuid::uuid4()->toString(); 
        return new self($id, $nom, $prenom, $email, password_hash($password, PASSWORD_BCRYPT),$role);
    }
        public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }
}
