<?php

namespace app\scr\api\application_core\application\useCase;

use src\api\dto\DetailOutilDto;

interface OutilsServiceInterface {
    public function AfficheOutils(): array;

    public function AfficheById(string $id_p): DetailOutilDto;
}
