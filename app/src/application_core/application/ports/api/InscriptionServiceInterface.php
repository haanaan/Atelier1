<?php

namespace charlymatloc\core\application\ports\api;

use charlymatloc\api\dto\InscriptionDTO;
use charlymatloc\core\domain\entities\Utilisateurs;

interface InscriptionServiceInterface {
    public function register(InscriptionDTO $inscriptionDTO): Utilisateurs;
}
