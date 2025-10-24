<?php
namespace charlymatloc\infra\repositories;

use PDO;
use charlymatloc\core\domain\entities\Reservation;
use charlymatloc\core\domain\entities\Utilisateurs;
use charlymatloc\core\domain\entities\Outils;
use charlymatloc\core\domain\entities\Categorie;
use charlymatloc\core\application\ports\spi\repositoryinterfaces\PDOReservationRepositoryInterface;

class PDOReservationRepository implements PDOReservationRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Récupère toutes les réservations avec les utilisateurs associés
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
            
            $outilsIds = $this->getOutilsForReservation($row['id']);
            
            $reservations[] = new Reservation(
                $row['id'],
                $row['datedebut'],
                $row['datefin'],
                (float)$row['montanttotal'],
                $row['statut'],
                $utilisateur,
                implode(',', $outilsIds) 
            );
        }
        return $reservations;
    }

    // Récupère les réservations par ID utilisateur
    public function FindByUserId(string $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT r.*, u.id AS utilisateur_id, u.nom AS utilisateur_nom, u.prenom AS utilisateur_prenom
            FROM reservation r
            JOIN utilisateurs u ON r.utilisateur_id = u.id
            WHERE r.utilisateur_id = :utilisateur_id
            ORDER BY r.datedebut DESC
        ");
        $stmt->execute(['utilisateur_id' => $userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $reservations = [];

        foreach ($rows as $row) {
            $utilisateur = new Utilisateurs(
                $row['utilisateur_id'], 
                $row['utilisateur_nom'] ?? '', 
                $row['utilisateur_prenom'] ?? '', 
                '', 
                '', 
                1
            );
            
            $outilsDetails = $this->getOutilsDetailsForReservation($row['id']);
            
            $reservations[] = new Reservation(
                $row['id'],
                $row['datedebut'],
                $row['datefin'],
                $row['montanttotal'],
                $row['statut'],
                $utilisateur,
                json_encode($outilsDetails)  
            );
        }

        return $reservations;
    }

    // Récupère une réservation par son ID
    public function FindById(string $id): ?Reservation
    {
        $stmt = $this->pdo->prepare("
            SELECT r.*, u.id AS utilisateur_id, u.nom AS utilisateur_nom, u.prenom AS utilisateur_prenom
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
            '', 
            '', 
            1
        );
        
        $outilsDetails = $this->getOutilsDetailsForReservation($row['id']);
        
        return new Reservation(
            $row['id'],
            $row['datedebut'],
            $row['datefin'],
            (float)$row['montanttotal'],
            $row['statut'],
            $utilisateur,
            json_encode($outilsDetails)  
        );
    }

    // Sauvegarde une nouvelle réservation
    public function Save(Reservation $reservation): void
    {
        $this->pdo->beginTransaction();
        
        try {
            // Insertion dans la table reservation
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
            
            // insertion dans la table de relation reservation_outils
            $outilsString = $reservation->getOutil();
            if (!empty($outilsString)) {
                if (substr($outilsString, 0, 1) === '[' || substr($outilsString, 0, 1) === '{') {
                    try {
                        $outilsData = json_decode($outilsString, true);
                        if (is_array($outilsData)) {
                            if (isset($outilsData[0]) && is_array($outilsData[0]) && isset($outilsData[0]['id'])) {
                                $outilsIds = array_map(function($outil) {
                                    return $outil['id'];
                                }, $outilsData);
                            } else {
                                $outilsIds = is_array($outilsData) ? $outilsData : [$outilsString];
                            }
                        } else {
                            $outilsIds = explode(',', $outilsString);
                        }
                    } catch (\Exception $e) {
                        $outilsIds = explode(',', $outilsString);
                    }
                } else {
                    $outilsIds = explode(',', $outilsString);
                }
                
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

    // Supprime une réservation par son ID
    public function Delete(string $id): void
    {
        $this->pdo->beginTransaction();
        
        try {
            $stmt = $this->pdo->prepare("DELETE FROM reservation_outils WHERE reservation_id = :id");
            $stmt->execute(['id' => $id]);
            
            $stmt = $this->pdo->prepare("DELETE FROM reservation WHERE id = :id");
            $stmt->execute(['id' => $id]);
            
            $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
    // Récupère les IDs des outils associés à une réservation
    private function getOutilsForReservation(string $reservationId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT outil_id FROM reservation_outils
            WHERE reservation_id = :reservation_id
        ");
        $stmt->execute(['reservation_id' => $reservationId]);
        
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Vérifie si un outil est disponible (nombre d'exemplaires non dépassé)
    public function EstOutilDisponible(string $outilId, string $dateDebut, string $dateFin): bool
    {
    $sql = "
        SELECT COUNT(*) 
        FROM reservation_outils ro
        JOIN reservation r ON r.id = ro.reservation_id
        WHERE ro.outil_id = :outil_id
          AND (
                (r.datedebut <= :dateFin AND r.datefin >= :dateDebut)
              )
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        'outil_id' => $outilId,
        'dateDebut' => $dateDebut,
        'dateFin' => $dateFin
    ]);

    // S’il y a 0 conflit → disponible
    return $stmt->fetchColumn() == 0;
    }
}