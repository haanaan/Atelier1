<?php

namespace charlymatloc\core\application\ports\spi\repositoryinterfaces;

use charlymatloc\core\domain\entities\Outils;

interface PDOOutilsRepositoryInterface
{
    public function FindAll(): array;
    public function FindbyId(string $id_p): Outils;

}
