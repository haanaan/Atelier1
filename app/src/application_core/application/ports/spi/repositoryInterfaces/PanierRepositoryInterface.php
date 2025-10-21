<?php
namespace charlymatloc\core\application\ports\spi\repositoryInterfaces;

use charlymatloc\core\domain\entities\Panier;

interface PanierRepositoryInterface
{
    public function findById(string $id): ?Panier;
}
