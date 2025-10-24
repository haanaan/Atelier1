<?php
namespace charlymatloc\core\application\usecases;

use charlymatloc\core\application\ports\api\ReservationServiceInterface;
use charlymatloc\core\application\ports\spi\repositoryinterfaces\PDOReservationRepositoryInterface;
use charlymatloc\api\dto\ReservationDto;
use charlymatloc\core\domain\entities\Reservation;

// Service de gestion des réservations
class ReservationService implements ReservationServiceInterface
{
    private PDOReservationRepositoryInterface $repository;

    public function __construct(PDOReservationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

// Récupère toutes les réservations
    public function AfficheReservations(): array
    {
        $reservations = $this->repository->FindAll();

        foreach ($reservations as $r) {
            $dtoList[] = new ReservationDto(
                $r->getId(),
                $r->getDateDebut(),
                $r->getDateFin(),
                (float)$r->getMontantTotal(),
                $r->getStatut(),
                $r->getUtilisateur()->getId(),
                [] 
            );
        }
        return $dtoList;
    }
// Récupère les réservations par ID utilisateur
    public function ListerReservationsByUserId(string $userId): array
    {
        $reservations = $this->repository->FindByUserId($userId);
        $dtoList = [];
        
        foreach ($reservations as $r) {
            $outilsDetails = [];
            try {
                $outilsJson = $r->getOutil();
                if (!empty($outilsJson)) {
                    $outilsDetails = json_decode($outilsJson, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $outilsDetails = [];
                    }
                }
            } catch (\Exception $e) {
                $outilsDetails = [];
            }
            
            $dtoList[] = new ReservationDto(
                $r->getId(),
                $r->getDateDebut(),
                $r->getDateFin(),
                (float)$r->getMontantTotal(),
                $r->getStatut(),
                $r->getUtilisateur()->getId(),
                $outilsDetails 
            );
        }
        return $dtoList;
    }

// Récupère une réservation par ID
    public function AfficheById(string $id): ?ReservationDto
    {
        $r = $this->repository->FindById($id);
        if (!$r) return null;
        
        $outilsDetails = [];
        try {
            $outilsJson = $r->getOutil();
            if (!empty($outilsJson)) {
                $outilsDetails = json_decode($outilsJson, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $outilsDetails = [];
                }
            }
        } catch (\Exception $e) {
            $outilsDetails = [];
        }
        
        return new ReservationDto(
            $r->getId(),
            $r->getDateDebut(),
            $r->getDateFin(),
            (float)$r->getMontantTotal(),
            $r->getStatut(),
            $r->getUtilisateur()->getId(),
            $outilsDetails 
        );
    }

    // Ajouter une nouvelle réservation
    public function AjouterReservation(Reservation $reservation): void
{
    $outilsString = $reservation->getOutil();
    $outilsIds = explode(',', $outilsString);

    foreach ($outilsIds as $outilId) {
        if (!$this->repository->EstOutilDisponible(
            trim($outilId),
            $reservation->getDateDebut(),
            $reservation->getDateFin()
        )) {
            throw new \Exception("L’outil $outilId n’est pas disponible sur cette période");
        }
    }

    $this->repository->Save($reservation);
}


// Supprimer une réservation par ID
public function SupprimerReservation(string $id): void
    {
        $this->repository->Delete($id);
    }
}