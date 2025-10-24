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
            $utilisateur = new Utilisateurs($row['utilisateur_id'], $row['utilisateur_nom'], '', '', '', 1);
            
            // Get associated tools
            $outilsIds = $this->getOutilsForReservation($row['id']);
            
            $reservations[] = new Reservation(
                $row['id'],
                $row['datedebut'],
                $row['datefin'],
                (float)$row['montanttotal'],
                $row['statut'],
                $utilisateur,
                implode(',', $outilsIds) // Join tool IDs as a comma-separated string
            );
        }
        return $reservations;
    }

    public function FindByUserId(string $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT r.*, u.id AS utilisateur_id, u.nom AS utilisateur_nom
            FROM reservation r
            JOIN utilisateurs u ON r.utilisateur_id = u.id
            WHERE r.utilisateur_id = :utilisateur_id
            ORDER BY r.datedebut DESC
        ");
        $stmt->execute(['utilisateur_id' => $userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $reservations = [];

        foreach ($rows as $row) {
            $utilisateur = new Utilisateurs($row['utilisateur_id'], $row['utilisateur_nom'], '', '', '', 1);
            
            // Get associated tools
            $outilsIds = $this->getOutilsForReservation($row['id']);
            
            $reservations[] = new Reservation(
                $row['id'],
                $row['datedebut'],
                $row['datefin'],
                $row['montanttotal'],
                $row['statut'],
                $utilisateur,
                implode(',', $outilsIds)
            );
        }

        return $reservations;
    }

    public function FindById(string $id): ?Reservation
    {
        $stmt = $this->pdo->prepare("
            SELECT r.*, u.id AS utilisateur_id, u.nom AS utilisateur_nom
            FROM reservation r
            JOIN utilisateurs u ON r.utilisateur_id = u.id
            WHERE r.id = :id
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        $utilisateur = new Utilisateurs($row['utilisateur_id'], $row['utilisateur_nom'], '', '', '', 1);
        
        $outilsIds = $this->getOutilsForReservation($row['id']);
        
        return new Reservation(
            $row['id'],
            $row['datedebut'],
            $row['datefin'],
            (float)$row['montanttotal'],
            $row['statut'],
            $utilisateur,
            implode(',', $outilsIds) 
        );
    }

    public function Save(Reservation $reservation): void
    {
        $this->pdo->beginTransaction();
        
        try {
            // Insert into the reservation table
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
            
            // Handle the outils (tools) relationship
            $outilsString = $reservation->getOutil();
            if (!empty($outilsString)) {
                $outilsIds = explode(',', $outilsString);
                
                foreach ($outilsIds as $outilId) {
                    if (empty($outilId)) continue;
                    
                    $stmt = $this->pdo->prepare("
                        INSERT INTO reservation_outils (reservation_id, outil_id)
                        VALUES (:reservation_id, :outil_id)
                    ");
                    $stmt->execute([
                        'reservation_id' => $reservation->getId(),
                        'outil_id' => trim($outilId)
                    ]);
                }
            }
            
            $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function Delete(string $id): void
    {
        $this->pdo->beginTransaction();
        
        try {
            // First delete from the relation table
            $stmt = $this->pdo->prepare("DELETE FROM reservation_outils WHERE reservation_id = :id");
            $stmt->execute(['id' => $id]);
            
            // Then delete the reservation
            $stmt = $this->pdo->prepare("DELETE FROM reservation WHERE id = :id");
            $stmt->execute(['id' => $id]);
            
            $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
    
    /**
     * Helper method to get tool IDs for a reservation
     */
    private function getOutilsForReservation(string $reservationId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT outil_id FROM reservation_outils
            WHERE reservation_id = :reservation_id
        ");
        $stmt->execute(['reservation_id' => $reservationId]);
        
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}