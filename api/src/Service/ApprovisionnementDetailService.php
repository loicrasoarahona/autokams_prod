<?php

namespace App\Service;

use App\Entity\Approvisionnement;
use App\Entity\ApprovisionnementDetail;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class ApprovisionnementDetailService
{
    private EntityManagerInterface $em;
    private ApprovisionnementService $approvisionnementService;
    private StockService $stockService;

    public function __construct(
        EntityManagerInterface $em,
        ApprovisionnementService $approvisionnementService,
        StockService $stockService
    ) {
        $this->em = $em;
        $this->approvisionnementService = $approvisionnementService;
        $this->stockService = $stockService;
    }

    public function quantiteRestante($id)
    {
        $approvisionnementDetail = $this->em->getRepository(ApprovisionnementDetail::class)->find($id);
        if (empty($approvisionnementDetail)) {
            throw new Exception("l'entité n'existe pas");
        }
        $produit = $approvisionnementDetail->getProduit();
        if (empty($produit)) {
            throw new Exception("l'entité n'a pas de produit");
        }
        $produitId = $produit->getId();
        $pointDeVente = $produit->getPointDeVente();
        if (empty($pointDeVente)) {
            throw new Exception("le produit n'a pas de point de vente");
        }
        $pointDeVenteId = $pointDeVente->getId();

        $repport = $this->stockService->getDernierRepportProduit($produitId, $pointDeVenteId);
        $dateDebut = null;
        $cumul = 0;
        if (!empty($repport)) {
            $cumul = $repport->getQuantite();
            $dateDebut = $repport->getDaty();
        }

        $nbVentes = $this->approvisionnementService->getQuantiteVenteProduit($produitId, $dateDebut);

        $listeApprovisionnementDetail = $this->approvisionnementService->getApprovisionnementDetailsProduit($produitId, $dateDebut);
        foreach ($listeApprovisionnementDetail as $row) {
            $bas = $cumul;
            $cumul += $row->getQuantite();
            if ($row->getId() == $approvisionnementDetail->getId()) {
                if ($nbVentes < $bas) {
                    return $row->getQuantite();
                }
                if ($nbVentes > $cumul) {
                    return 0;
                }

                return $cumul - $nbVentes;
            }
        }

        return 0;
    }
}
