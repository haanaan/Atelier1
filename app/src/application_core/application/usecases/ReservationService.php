<?php
namespace charlymatloc\core\application\usecases;

use charlymatloc\api\dto\ReservationDto;
use charlymatloc\core\application\ports\api\ReservationServiceInterface;
use charlymatloc\core\application\ports\spi\repositoryinterfaces\PDOReservationRepositoryInterface;
use charlymatloc\core\domain\entities\Reservation;

class ReservationService implements ReservationServiceInterface
{
    private PDOReservationRepositoryInterface $repo;

    public function __construct(PDOReservationRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function ListerReservations(): array
    {
        $reservations = $this->repo->FindAll();
        $dtoList = [];

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

    public function ListerReservationsByUserId(string $userId): array
    {
        $reservations = $this->repo->FindByUserId($userId);
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

    public function TrouverReservation(string $id): ?ReservationDto
    {
        $r = $this->repo->FindById($id);
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

    public function AjouterReservation(Reservation $reservation): void
    {
        $this->repo->Save($reservation);
    }

    public function SupprimerReservation(string $id): void
    {
        $this->repo->Delete($id);
    }
}