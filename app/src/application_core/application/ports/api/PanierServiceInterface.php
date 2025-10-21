<?php
namespace charlymatloc\core\application\ports\api;

use charlymatloc\api\dto\PanierDTO;

interface PanierServiceInterface
{
    public function getPanierAvecTotal(string $id): ?PanierDTO;
}
