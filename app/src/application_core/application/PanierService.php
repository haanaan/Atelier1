<?php
namespace charlymatloc\core\application\services;

use charlymatloc\core\application\ports\spi\repositoryInterfaces\PanierRepositoryInterface;
use charlymatloc\core\application\ports\api\servicesInterfaces\PanierServiceInterface;

class PanierService implements PanierServiceInterface
{
    private PanierRepositoryInterface $panierRepository;

    public function __construct(PanierRepositoryInterface $panierRepository)
    {
        $this->panierRepository = $panierRepository;
    }

    public function getPanierAvecTotal(string $id): ?array
    {
        $panier = $this->panierRepository->findById($id);
        if ($panier === null)
            return null;

        $total = 0;
        $outils = $panier->getOutils();

        foreach ($outils as $outil) {
            $total += $outil->getMontant();
        }

        return [
            'id' => $panier->getId(),
            'outils' => array_map(fn($o) => [
                'id' => $o->getId(),
                'nom' => $o->getNom(),
                'prix' => $o->getMontant()
            ], $outils),
            'total' => round($total, 2)
        ];
    }
}
