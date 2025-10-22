<?php

namespace charlymatloc\core\application\usecases;

use charlymatloc\api\dto\DetailOutilDto;
use charlymatloc\api\dto\OutilCatalogue;
use charlymatloc\core\application\ports\api\OutilsServiceInterface;
use charlymatloc\core\application\ports\spi\repositoryinterfaces\PDOOutilsRepositoryInterface;





class OutilsService implements OutilsServiceInterface
{
    private PDOOutilsRepositoryInterface $outilsRepository;
    public function __construct(PDOOutilsRepositoryInterface $outilsRepository)
    {
        $this->outilsRepository = $outilsRepository;
    }

    public function AfficheOutils(): array
    {
        $outils = $this->outilsRepository->findAll();
        $outilsDTO = [];

        foreach ($outils as $outil) {
            $outilsDTO[] = new OutilCatalogue(
                $outil->getId(),
                $outil->getNom(),
                $outil->getImage(),
                $outil->getExemplaires()
            );
        }

        return $outilsDTO;
    }

    public function AfficheById(string $id_p): DetailOutilDto
    {
        $outil = $this->outilsRepository->findbyId($id_p);

        if (!$outil) {
            throw new \Exception("Outil avec l'ID $id_p introuvable");
        }

        return new DetailOutilDto(
            $outil->getId(),
            $outil->getNom(),
            $outil->getDescription(),
            $outil->getImage(),
            $outil->getCategorie()->getNom(),
            $outil->getMontant()
        );
    }
}