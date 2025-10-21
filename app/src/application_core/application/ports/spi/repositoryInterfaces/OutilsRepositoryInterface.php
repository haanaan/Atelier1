<?php
namespace charlymatloc\core\application\ports\spi\repositoryInterfaces;

use charlymatloc\core\application\entities\Outils;

interface OutilsRepositoryInterface
{
    /** @return Outils[] */
    public function findAll(): array;
    public function findbyId(string $id): ?Outils;
}