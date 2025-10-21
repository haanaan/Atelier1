<?php

namespace charlymatloc\core\application\ports\spi\repositoryinterfaces;

use charlymatloc\core\domain\entities\Outils;

interface PDOOutilsRepositoryInterface
{
    public function findAll(): array;
    public function findbyId(string $id_p): Outils;

}
