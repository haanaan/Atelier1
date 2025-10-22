<?php
namespace charlymatloc\infra\repositories;

use PDO;
use charlymatloc\core\domain\entities\Reservation;
use charlymatloc\core\domain\entities\Utilisateurs;
use charlymatloc\core\application\ports\spi\repositoryinterfaces\PDOReservationRepositoryInterface;

class PDOReservationRepository implements PDOReservationRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function FindAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT r.*, u.id AS utilisateur_id, u.nom AS utilisateur_nom
            FROM reservation r
            JOIN utilisateurs u ON r.utilisateur_id = u.id
            ORDER BY r.datedebut DESC
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $reservations = [];

        foreach ($rows as $row) {
            $utilisateur = new Utilisateurs($row['utilisateur_id'], $row['utilisateur_nom'], '', '', '');
            $reservations[] = new Reservation(
                $row['id'],
                $row['datedebut'],
                $row['datefin'],
                $row['montanttotal'],
                $row['statut'],
                $utilisateur,
                $row['outils'] ?? ''
            );
        }
        return $reservations;
    }

    public function FindById(string $id): ?Reservation
    {
        $stmt = $this->pdo->prepare("SELECT * FROM reservation WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        $utilisateur = new Utilisateurs($row['utilisateur_id'], '', '', '', '');
        return new Reservation(
            $row['id'],
            $row['datedebut'],
            $row['datefin'],
            $row['montanttotal'],
            $row['statut'],
            $utilisateur,
            $row['outils'] ?? ''
        );
    }

    public function Save(Reservation $reservation): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO reservation (id, utilisateur_id, datedebut, datefin, montanttotal, statut, outils)
            VALUES (:id, :utilisateur_id, :datedebut, :datefin, :montanttotal, :statut, :outils)
        ");
        $stmt->execute([
            'id' => $reservation->getId(),
            'utilisateur_id' => $reservation->getUtilisateur()->getId(),
            'datedebut' => $reservation->getDateDebut(),
            'datefin' => $reservation->getDateFin(),
            'montanttotal' => $reservation->getMontantTotal(),
            'statut' => $reservation->getStatut(),
            'outils' => $reservation->getOutil()
        ]);
    }

    public function Delete(string $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM reservation WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
