<?php

namespace charlymatloc\core\application\ports\spi\repositoryinterfaces;

use charlymatloc\core\domain\entities\Outils;

interface PDOOutilsRepositoryInterface
{
    public function GetAllOutils(): array;
    public function GetOutil(string $id_p): Outils;
    
}
