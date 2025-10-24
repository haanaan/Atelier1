<?php
namespace charlymatloc\core\application\ports\api;

use charlymatloc\api\dto\ReservationDto;
use charlymatloc\core\domain\entities\Reservation;

interface ReservationServiceInterface
{
    public function AfficheReservations(): array;
    public function AfficheById(string $id): ?ReservationDto;
    public function AjouterReservation(Reservation $reservation): void;
    public function SupprimerReservation(string $id): void;
    public function ListerReservationsByUserId(string $userId): array;
}
