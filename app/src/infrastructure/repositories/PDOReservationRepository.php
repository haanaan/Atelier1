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
            SELECT 
                r.id, r.datedebut, r.datefin, r.montanttotal, r.statut,
                u.id AS utilisateur_id, 
                u.nom AS utilisateur_nom, 
                u.prenom AS utilisateur_prenom, 
                u.email AS utilisateur_email, 
                u.password AS utilisateur_password,
                COALESCE(u.role, 1) AS utilisateur_role
            FROM reservation r
            JOIN utilisateurs u ON r.utilisateur_id = u.id
            ORDER BY r.datedebut DESC
        ");

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $reservations = [];

        foreach ($rows as $row) {
            $utilisateur = new Utilisateurs(
                $row['utilisateur_id'],
                $row['utilisateur_nom'] ?? '',
                $row['utilisateur_prenom'] ?? '',
                $row['utilisateur_email'] ?? '',
                $row['utilisateur_password'] ?? '',
                (int)($row['utilisateur_role'] ?? 1) // ✅ ajout du rôle
            );

            $outils = $this->findOutilsForReservation($row['id']);

            $reservations[] = new Reservation(
                $row['id'],
                $row['datedebut'],
                $row['datefin'],
                (float)$row['montanttotal'],
                $row['statut'],
                $utilisateur,
                $outils
            );
        }

        return $reservations;
    }

    public function FindById(string $id): ?Reservation
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                r.id, r.datedebut, r.datefin, r.montanttotal, r.statut,
                u.id AS utilisateur_id, 
                u.nom AS utilisateur_nom, 
                u.prenom AS utilisateur_prenom, 
                u.email AS utilisateur_email, 
                u.password AS utilisateur_password,
                COALESCE(u.role, 1) AS utilisateur_role
            FROM reservation r
            JOIN utilisateurs u ON r.utilisateur_id = u.id
            WHERE r.id = :id
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        $utilisateur = new Utilisateurs(
            $row['utilisateur_id'],
            $row['utilisateur_nom'] ?? '',
            $row['utilisateur_prenom'] ?? '',
            $row['utilisateur_email'] ?? '',
            $row['utilisateur_password'] ?? '',
            (int)($row['utilisateur_role'] ?? 1) // ✅ ajout du rôle
        );

        $outils = $this->findOutilsForReservation($id);

        return new Reservation(
            $row['id'],
            $row['datedebut'],
            $row['datefin'],
            (float)$row['montanttotal'],
            $row['statut'],
            $utilisateur,
            $outils
        );
    }

    public function Save(Reservation $reservation): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO reservation (id, utilisateur_id, datedebut, datefin, montanttotal, statut)
            VALUES (:id, :utilisateur_id, :datedebut, :datefin, :montanttotal, :statut)
        ");
        $stmt->execute([
            'id' => $reservation->getId(),
            'utilisateur_id' => $reservation->getUtilisateur()->getId(),
            'datedebut' => $reservation->getDateDebut(),
            'datefin' => $reservation->getDateFin(),
            'montanttotal' => $reservation->getMontantTotal(),
            'statut' => $reservation->getStatut()
        ]);

        foreach ($reservation->getOutil() as $outil) {
            $this->pdo->prepare("
                INSERT INTO reservation_outils (reservation_id, outil_id)
                VALUES (:reservation_id, :outil_id)
            ")->execute([
                'reservation_id' => $reservation->getId(),
                'outil_id' => is_array($outil) ? $outil['id'] : $outil
            ]);
        }
    }

    public function Delete(string $id): void
    {
        $this->pdo->prepare("DELETE FROM reservation_outils WHERE reservation_id = :id")
            ->execute(['id' => $id]);

        $this->pdo->prepare("DELETE FROM reservation WHERE id = :id")
            ->execute(['id' => $id]);
    }

    private function findOutilsForReservation(string $reservationId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT o.id, o.nom, o.montant
            FROM reservation_outils ro
            JOIN outils o ON ro.outil_id = o.id
            WHERE ro.reservation_id = :id
        ");
        $stmt->execute(['id' => $reservationId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
