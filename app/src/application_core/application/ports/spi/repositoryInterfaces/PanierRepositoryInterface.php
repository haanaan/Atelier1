<?php
namespace charlymatloc\core\application\ports\spi\repositoryInterfaces;

use charlymatloc\core\application\entities\Panier;

interface PanierRepositoryInterface
{
    public function findById(string $id): ?Panier;
}
