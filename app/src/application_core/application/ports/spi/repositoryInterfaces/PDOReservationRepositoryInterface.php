<?php
namespace charlymatloc\core\application\ports\spi\repositoryinterfaces;

use charlymatloc\core\domain\entities\Reservation;

interface PDOReservationRepositoryInterface
{
    public function FindAll(): array;
    public function FindById(string $id): ?Reservation;
    public function Save(Reservation $reservation): void;
    public function Delete(string $id): void;
}
