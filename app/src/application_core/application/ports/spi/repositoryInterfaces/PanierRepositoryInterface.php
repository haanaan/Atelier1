<?php
namespace charlymatloc\core\application\ports\spi\repositoryInterfaces;

use charlymatloc\api\dto\PanierDTO;
use charlymatloc\core\domain\entities\Panier;
use function DI\string;


interface PanierRepositoryInterface
{
    public function findById(string $id): ?Panier;
    public function getOrCreatePanier(string $userId):PanierDTO;
    public function getByUser(string $userId):PanierDTO;
    public function getItems(string $panierId):PanierDTO;
    public function  removeItem(string $panierId, string $outilId): PanierDTO;
    public function addItem(string $panierId, string $outilId):bool;
    public function clearItems(string $panierId): bool;

}
