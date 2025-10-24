<?php
namespace charlymatloc\core\application\usecases;

use charlymatloc\core\application\ports\api\ReservationServiceInterface;
use charlymatloc\core\application\ports\spi\repositoryinterfaces\PDOReservationRepositoryInterface;
use charlymatloc\api\dto\ReservationDto;
use charlymatloc\core\domain\entities\Reservation;

class ReservationService implements ReservationServiceInterface
{
    private PDOReservationRepositoryInterface $repository;

    public function __construct(PDOReservationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

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
                $r->getUtilisateur()->getId()
            );
        }

        return $dtoList;
    }
public function ListerReservationsByUserId(string $userId): array
    {
        $reservations = $this->repository->FindByUserId($userId);
        $dtoList = [];

        foreach ($reservations as $r) {
            $dtoList[] = new ReservationDto(
                $r->getId(),
                $r->getDateDebut(),
                $r->getDateFin(),
                (float)$r->getMontantTotal(),
                $r->getStatut(),
                $r->getUtilisateur()->getId()
            );
        }

        return $dtoList;
    }


    public function AfficheById(string $id): ?ReservationDto
    {
        $r = $this->repository->FindById($id);
        if (!$r) return null;

        return new ReservationDto(
            $r->getId(),
            $r->getDateDebut(),
            $r->getDateFin(),
            (float)$r->getMontantTotal(),
            $r->getStatut(),
            $r->getUtilisateur()->getId()
        );
    }

    public function AjouterReservation(Reservation $reservation): void
    {
        $this->repository->Save($reservation);
    }

    public function SupprimerReservation(string $id): void
    {
        $this->repository->Delete($id);
    }
}
