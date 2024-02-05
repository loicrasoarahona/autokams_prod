<?php

namespace App\Service;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class RepportNouveauService
{
    public function __construct(
        private StockService $stockService,
        private EntityManagerInterface $em,
        private ApprovisionnementService $approvisionnementService
    ) {
    }

    public function quantiteRestante()
    {
    }

    public function quantiteRestanteParProduit($produitId)
    {
        $produit = $this->em->getRepository(Produit::class)->find($produitId);
        if (empty($produit)) {
            throw new Exception("Le produit n'existe pas");
        }
        $pointDeVente = $produit->getPointDeVente();
        if (empty($pointDeVente)) {
            throw new Exception("Le produit n'a pas de point de vente");
        }

        $currentRepport = $this->stockService->getDernierRepportProduit($produitId, $pointDeVente->getId());
        if (empty($currentRepport)) {
            throw new Exception("Aucun repport existant");
        }
        $dateDebut = $currentRepport->getDaty();
        $quantite = 0;
        $quantite = $currentRepport->getQuantite();

        $nbVentes = $this->approvisionnementService->getQuantiteVenteProduit($produitId, $dateDebut);
        $quantite -= $nbVentes;

        if ($quantite <= 0) {
            return 0;
        }
        return $quantite;
    }
}
