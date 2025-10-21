<?php
namespace charlymatloc\core\application\ports\api\servicesInterfaces;

interface PanierServiceInterface
{
    public function getPanierAvecTotal(string $id): ?array;
}
