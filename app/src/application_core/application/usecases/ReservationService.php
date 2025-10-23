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

        return array_map(fn($r) => new ReservationDto($r), $reservations);
    }

    public function AfficheById(string $id): ?ReservationDto
    {
        $r = $this->repository->FindById($id);
        if (!$r) return null;

        return new ReservationDto($r);
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
