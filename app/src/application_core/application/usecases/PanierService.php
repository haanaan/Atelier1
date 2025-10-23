<?php

namespace charlymatloc\core\application\usecases;

use charlymatloc\core\application\ports\spi\repositoryInterfaces\PanierRepositoryInterface;
use charlymatloc\api\dto\PanierDTO;
use charlymatloc\core\application\ports\api\PanierServiceInterface;

class PanierService implements PanierServiceInterface
{
    private PanierRepositoryInterface $panierRepository;

    public function __construct(PanierRepositoryInterface $panierRepository)
    {
        $this->panierRepository = $panierRepository;
    }

    public function getPanierAvecTotal(string $id): ?PanierDTO
    {
        $panier = $this->panierRepository->findById($id);
        if ($panier === null) {
            return null;  
        }

        $total = 0;
        $outils = $panier->getOutils();  

        foreach ($outils as $outil) {
            $total += $outil->getMontant();
        }

        return new PanierDTO(
            $panier->getId(),
            $panier->getUtilisateur()->getNom(), 
            array_map(fn($o) => [
                'id' => $o->getId(),
                'nom' => $o->getNom(),
                'prix' => $o->getMontant()
            ], $outils),
            round($total, 2)  
        );
    }
    public function getPanierByUser(string $userId): PanierDTO
    {
        return $this->panierRepository->getByUser($userId);
    }

    public function addOutilToPanier(string $userId, string $outilId): PanierDTO
    {
        $panier = $this->panierRepository->getOrCreatePanier($userId);
        $this->panierRepository->addItem($panier->id, $outilId);

        return $this->panierRepository->getItems($panier->id);
    }

    public function removeOutilFromPanier(string $userId, string $outilId): PanierDTO
    {
        $panier = $this->panierRepository->getByUser($userId);
        $this->panierRepository->removeItem($panier->id, $outilId);

        return $this->panierRepository->getItems($panier->id);
    }

    public function clearPanier(string $userId): bool
    {
        $panier = $this->panierRepository->getByUser($userId);
        return $this->panierRepository->clearItems($panier->id);
    }
}
