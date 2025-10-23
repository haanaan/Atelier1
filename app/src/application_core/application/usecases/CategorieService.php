<?php

namespace charlymatloc\core\application\usecases;

use charlymatloc\core\application\ports\api\CategorieServiceInterface;
use charlymatloc\core\application\ports\spi\repositoryInterfaces\PDOCategorieRepositoryInterface;
use charlymatloc\api\dto\CategorieDTO;
use charlymatloc\core\domain\entities\Categorie;

class CategorieService implements CategorieServiceInterface
{
    private PDOCategorieRepositoryInterface $categorieRepository;

    public function __construct(PDOCategorieRepositoryInterface $categorieRepository)
    {
        $this->categorieRepository = $categorieRepository;
    }

    public function getAllCategories(): array
    {
        $categories = $this->categorieRepository->findAll();
        return array_map(
            fn(Categorie $categorie) => new CategorieDTO(
                $categorie->getId(),
                $categorie->getNom(),
                $categorie->getDescription()
            ),
            $categories
        );
    }

    public function getCategorieById(string $id): ?CategorieDTO
    {
        $categorie = $this->categorieRepository->findById($id);
        if (!$categorie) {
            return null;
        }

        return new CategorieDTO(
            $categorie->getId(),
            $categorie->getNom(),
            $categorie->getDescription()
        );
    }
}
