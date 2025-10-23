<?php

namespace charlymatloc\core\application\ports\spi\repositoryinterfaces;

use charlymatloc\core\domain\entities\Categorie;

interface PDOCategorieRepositoryInterface
{
    /** @return Categorie[] */
    public function findAll(): array;

    public function findById(string $id): ?Categorie;
}
