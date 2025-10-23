<?php
namespace charlymatloc\core\application\ports\api;

use charlymatloc\api\dto\ReservationDto;
use charlymatloc\core\domain\entities\Reservation;

interface ReservationServiceInterface
{
    public function ListerReservations(): array;
    public function TrouverReservation(string $id): ?ReservationDto;
    public function AjouterReservation(Reservation $reservation): void;
    public function SupprimerReservation(string $id): void;
}
