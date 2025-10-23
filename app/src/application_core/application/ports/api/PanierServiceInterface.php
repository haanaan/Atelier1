<?php
namespace charlymatloc\core\application\ports\api;

use charlymatloc\api\dto\PanierDTO;

interface PanierServiceInterface
{
    public function getPanierAvecTotal(string $id): ?PanierDTO;
    public function getPanierByUser(string $userId): PanierDTO;
    public function addOutilToPanier(string $userId, string $outilId): PanierDTO;
    public function removeOutilFromPanier(string $userId, string $outilId): PanierDTO;
    public function clearPanier(string $userId): bool;

}
