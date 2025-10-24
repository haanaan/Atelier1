<?php

namespace charlymatloc\core\application\ports\api;

use charlymatloc\api\dto\CategorieDTO;

interface CategorieServiceInterface
{
    /**
     * @return CategorieDTO[]
     */
    public function getAllCategories(): array;

    public function getCategorieById(string $id): ?CategorieDTO;
}
