<?php

namespace charlymatloc\core\application\ports\api;

use charlymatloc\api\dto\DetailOutilDto;

interface OutilsServiceInterface {
    public function AfficheOutils(): array;

    public function AfficheById(string $id_p): DetailOutilDto;
}
