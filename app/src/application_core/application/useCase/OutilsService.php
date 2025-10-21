<?php

namespace app\scr\api\application_core\application\useCase;

use charlymatloc\core\application\ports\api\ServiceOutilsInterface;
use charlymatloc\infra\repositories\PDOOutilsRepository;
use src\api\dto\DetailOutilDto;
use src\api\dto\OutilCatalogue;



class OutilsService implements OutilsServiceInterface {
    private PDOOutilsRepository $outilsRepository;
    public function __construct( PDOOutilsRepository $outilsRepository) {
        $this->outilsRepository=$outilsRepository;
    }

    public function AfficheOutils(): array {
<<<<<<< HEAD
        $outils = $this->outilsRepository->findAll();
=======
        $outils = $this->outilsRepository->FindAll();
>>>>>>> 14df4787fca4b490f1507cfb1ede2d18354e2bc3
        $outilsDTO = [];

        foreach ($outils as $outil) {
            $outilsDTO[] = new OutilCatalogue(
                $outil->getNom(),
                $outil->getImage(),
                $outil->getNombreExemplaires()
            );
        }

        return $outilsDTO;
    }

    public function AfficheById(string $id_p): DetailOutilDto {
        $outil = $this->outilsRepository->findbyId($id_p);

        if (!$outil) {
            throw new \Exception("Outil avec l'ID $id_p introuvable");
        }

        return new DetailOutilDto(
            $outil->getNom(),
            $outil->getDescription(),
            $outil->getImage(),
            $outil->getCategorie()->getNom() 
        );
    }
}