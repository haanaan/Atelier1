<?php

namespace charlymatloc\core\application\usecases;

use charlymatloc\api\dto\DetailOutilDto;
use charlymatloc\api\dto\OutilCatalogue;
use charlymatloc\core\application\ports\api\OutilsServiceInterface;
use charlymatloc\infra\repositories\PDOOutilsRepository;





class OutilsService implements OutilsServiceInterface{
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
                $outil->getExemplaires()
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
            $outil->getCategorie()->getNom() ,
            $outil->getMontant()
        );
    }
}