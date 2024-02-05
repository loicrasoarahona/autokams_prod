<?php

namespace App\Service;

use App\Entity\ApprovisionnementDetail;
use App\Entity\Produit;
use App\Entity\RepportANouveau;
use App\Entity\VenteDetail;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Serializer\SerializerInterface;

class ProduitService
{
    private StockService $stockService;
    private ApprovisionnementService $approvisionnementService;
    private EntityManagerInterface $em;
    private SerializerInterface $serializer;
    private ApprovisionnementDetailService $approvisionnementDetailService;


    public function __construct(
        StockService $stockService,
        ApprovisionnementService $approvisionnementService,
        EntityManagerInterface $em,
        SerializerInterface $serializer,
        ApprovisionnementDetailService $approvisionnementDetailService
    ) {
        $this->stockService = $stockService;
        $this->approvisionnementService = $approvisionnementService;
        $this->approvisionnementService->setProduitService($this);
        $this->em = $em;
        $this->serializer = $serializer;
        $this->approvisionnementDetailService = $approvisionnementDetailService;
    }

    public function getClassementProduitVente()
    {
        $sql = "select produit.*,sum(quantite * vente_detail.prix) as total_ventes from vente_detail join produit on produit_id=produit.id group by produit_id order by total_ventes desc limit 30";
        $results = $this->em->getConnection()->fetchAllAssociative($sql);

        dd($results);
    }

    public function getPrixTotalVentes($id)
    {
        $retour = 0;
        $produit = $this->em->getRepository(Produit::class)->find($id);
        if (empty($produit)) {
            throw new Exception("Le produit n'existe pas");
        }
        $pointDeVente = $produit->getPointDeVente();
        if (empty($pointDeVente)) {
            throw new Exception("Le produit n'a pas de point de vente");
        }
        $dateDebut = null;
        $repport = $this->stockService->getDernierRepportProduit($id, $pointDeVente->getId());
        if (!empty($repport)) {
            $dateDebut = $repport->getDaty();
        }

        $venteDetails = $this->approvisionnementService->getVenteDetailsProduit($id, $dateDebut);
        foreach ($venteDetails as $row) {
            $retour += $row->getPrix() * $row->getQuantite();
        }

        return $retour;
    }

    public function setApprovisionnementService(ApprovisionnementService $service)
    {
        $this->approvisionnementService = $service;
    }
    public function setStockService(StockService $service)
    {
        $this->stockService = $service;
    }

    public function getPrix($id)
    {
        $produit = $this->em->getRepository(Produit::class)->find($id);
        if (empty($produit)) {
            throw new Exception("Le produit n'existe pas");
        }
        $pointDeVente = $produit->getPointDeVente();
        if (empty($pointDeVente)) {
            throw new Exception("Le produit n'a pas de point de vente");
        }
        $retour = 0;
        $repport = $this->stockService->getDernierRepportProduit($id, $pointDeVente->getId());
        $dateDebut = null;

        $retour = $repport->getPrixUnit();
        if ($repport) {
            $dateDebut = $repport->getDaty();
            $nbVentes = $this->approvisionnementService->getQuantiteVenteProduit($id, $dateDebut);
            if ($nbVentes > $repport->getQuantite()) {
                $retour = 0;
            }
        }

        $approvisionnementDetails = $this->approvisionnementService->getApprovisionnementDetailsProduit($id, $dateDebut);
        // je prends juste le dernier
        $length = count($approvisionnementDetails);
        if ($length > 0) {
            $retour = $approvisionnementDetails[$length - 1]->getPrixVente();
        }

        return $retour;
    }

    // cette fonction est annulée temporairement
    // public function getPrix($id)
    // {
    //     $produit = $this->em->getRepository(Produit::class)->find($id);
    //     if (empty($produit)) {
    //         throw new Exception("Le produit n'existe pas");
    //     }
    //     $pointDeVente = $produit->getPointDeVente();
    //     if (empty($pointDeVente)) {
    //         throw new Exception("Le produit n'a pas de point de vente");
    //     }
    //     $retour = 0;
    //     $repport = $this->stockService->getDernierRepportProduit($id, $pointDeVente->getId());
    //     $dateDebut = null;


    //     $retour = $repport->getPrixUnit();
    //     if ($repport) {
    //         $dateDebut = $repport->getDaty();
    //         $nbVentes = $this->approvisionnementService->getQuantiteVenteProduit($id, $dateDebut);
    //         if ($nbVentes > $repport->getQuantite()) {
    //             $retour = 0;
    //         }
    //     }

    //     $approvisionnementDetails = $this->approvisionnementService->getApprovisionnementDetailsProduit($id, $dateDebut);
    //     foreach ($approvisionnementDetails as $row) {
    //         $quantiteRestante = $this->approvisionnementDetailService->quantiteRestante($row->getId());
    //         $prix = $row->getPrixVente();
    //         if ($quantiteRestante > 0 && $prix > $retour) {
    //             $retour = $prix;
    //         }
    //     }

    //     return $retour;
    // }

    // public function getQuantiteApprovisionnement(Produit $produit, $dateDebut, $dateFin, int $pointDeVenteId)
    // {
    //     $retour = 0;
    //     // quantification vérification
    //     $quantificationDefaut = $produit->getQuantification();
    //     if (!$quantificationDefaut) {
    //         throw new Exception("Le produit n'a aucune quantification par défaut", 500);
    //     }

    //     $query = $this->em->getRepository(ApprovisionnementDetail::class)
    //         ->createQueryBuilder('approvisionnement')
    //         ->select()
    //         ->join('approvisionnement.produit', 'produit')
    //         ->join('approvisionnement.approvisionnement', 'mere')
    //         ->join('mere.pointDeVente', 'pointDeVente')
    //         ->where('pointDeVente.id=:pointDeVenteId')
    //         ->andWhere('produit.id=:produitId')
    //         ->andWhere('approvisionnement.empty=false')
    //         ->setParameters([
    //             "pointDeVenteId" => $pointDeVenteId,
    //             "produitId" => $produit->getId()
    //         ]);

    //     if ($dateDebut != null) {
    //         $query->andWhere('mere.daty>=:dateDebut');
    //         $query->setParameter('dateDebut', $dateDebut);
    //     }
    //     if ($dateFin != null) {
    //         $query->andWhere('mere.daty<=:dateFin');
    //         $query->setParameter('dateFin', $dateFin);
    //     }

    //     $approvisionnements = $query
    //         ->getQuery()
    //         ->getResult();

    //     $normalized = $this->serializer->normalize($approvisionnements, null, ['groups' => ['approvisionnementDetail:collection', 'quantification:collection']]);

    //     // conversion de chaque quantification
    //     $quantificationEquivalences = $produit->getQuantificationEquivalences();
    //     foreach ($approvisionnements as $appro) {
    //         $quantite = $appro->getQuantite();
    //         $quantification = $appro->getQuantification();
    //         // quantification par defaut
    //         if ($quantification->getId() == $quantificationDefaut->getId()) {
    //             $retour += $quantite;
    //             continue;
    //         }
    //         // sinon, chercher quantitication equivalence
    //         $trouver = 0;
    //         foreach ($quantificationEquivalences as $equivalence) {
    //             if ($equivalence->getQuantification()->getId() == $quantification->getId()) {
    //                 // verification si la valeur existe
    //                 if (empty($equivalence->getValeur())) {
    //                     throw new Exception("Veuillez renseigner valeur de la quantification ‘" . $quantification->getNom() . "‘ dans le produit", 500);
    //                 }
    //                 $retour += $quantite / $equivalence->getValeur();
    //                 $trouver = 1;
    //                 break;
    //             }
    //         }
    //         if (!$trouver)
    //             throw new Exception("Veuillez renseigner la quantification ‘" . $quantification->getNom() . "‘ dans le produit", 500);
    //     }
    //     return ($retour);
    // }

    // public function getQuantiteVente(Produit $produit, $dateDebut, $dateFin, int $pointDeVenteId)
    // {
    //     $retour = 0;
    //     // quantification vérification
    //     $quantificationDefaut = $produit->getQuantification();
    //     if (!$quantificationDefaut) {
    //         throw new Exception("Le produit n'a aucune quantification par défaut", 500);
    //     }

    //     $query = $this->em->getRepository(VenteDetail::class)
    //         ->createQueryBuilder('venteDetail')
    //         ->select()
    //         ->join('venteDetail.produit', 'produit')
    //         ->join('venteDetail.vente', 'mere')
    //         ->join('mere.pointDeVente', 'pointDeVente')
    //         ->where('pointDeVente.id=:pointDeVenteId')
    //         ->andWhere('produit.id=:produitId')
    //         ->setParameters([
    //             "pointDeVenteId" => $pointDeVenteId,
    //             "produitId" => $produit->getId()
    //         ]);

    //     if ($dateDebut != null) {
    //         $query->andWhere('mere.daty>=:dateDebut');
    //         $query->setParameter('dateDebut', $dateDebut);
    //     }
    //     if ($dateFin != null) {
    //         $query->andWhere('mere.daty<=:dateFin');
    //         $query->setParameter('dateFin', $dateFin);
    //     }

    //     $venteDetails = $query
    //         ->getQuery()
    //         ->getResult();

    //     $quantificationEquivalences = $produit->getQuantificationEquivalences();
    //     foreach ($venteDetails as $detail) {
    //         $quantite = $detail->getQuantite();
    //         $quantification = $detail->getUnite();

    //         // si quantificationDefaut
    //         if ($quantification->getId() == $quantificationDefaut->getId()) {
    //             $retour += $quantite;
    //             continue;
    //         }
    //         // sinon chercher equivalence
    //         $trouver = 0;
    //         foreach ($quantificationEquivalences as $equivalence) {
    //             if ($equivalence->getQuantification()->getId() == $quantification->getId()) {
    //                 $trouver = 1;
    //                 $retour += $quantite / $equivalence->getValeur();
    //                 break;
    //             }
    //         }
    //         if (!$trouver) {
    //             throw new Exception("Veuillez renseigner la quantification ‘" . $quantification->getNom() . "‘ dans le produit", 500);
    //         }
    //     }

    //     $normalized = $this->serializer->normalize($venteDetails, null, ['groups' => ['venteDetail:collection', 'quantification:collection']]);

    //     return ($retour);
    // }

    public function getCurrentInventaire(Produit $produit, DateTime $date = new DateTime())
    {
        try {
            if (empty($produit)) {
                throw new Exception("Le produit n'existe pas", 500);
            }

            $results = $this->em->getRepository(RepportANouveau::class)->createQueryBuilder('repport')
                ->select()
                ->join('repport.produit', 'produit')
                ->where('produit.id=:produitId')
                ->andWhere('repport.daty <= :currentDate')
                ->setParameters([
                    'produitId' => $produit->getId(),
                    'currentDate' => $date
                ])
                ->addOrderBy('repport.daty', 'desc')
                ->setMaxResults(1)
                ->getQuery()
                ->getResult();

            if (!empty($results[0])) {
                return $results[0];
            }
            return null;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getApprovisionnementDetailsParDate(Produit $produit, Datetime $dateDebut, DateTime $dateFin)
    {


        $approvisionnements = $this->em->getRepository(ApprovisionnementDetail::class)
            ->createQueryBuilder('approvisionnement')
            ->select()
            ->join('approvisionnement.produit', 'produit')
            ->join('approvisionnement.approvisionnement', 'mere')
            ->join('mere.pointDeVente', 'pointDeVente')
            ->where('pointDeVente.id=:pointDeVenteId')
            ->andWhere('mere.daty<=:dateNow')
            ->andWhere('mere.daty>=:dateRepport')
            ->andWhere('produit.id=:produitId')
            ->setParameters([
                "pointDeVenteId" => $produit->getPointDeVente()->getId(),
                "dateNow" => $dateFin,
                "dateRepport" => $dateDebut,
                "produitId" => $produit->getId()
            ])
            ->addOrderBy('mere.daty', 'desc')
            ->getQuery()
            ->getResult();

        return $approvisionnements;
    }

    public function getVenteDetailsParDate(Produit $produit, DateTime $dateDebut, Datetime $dateFin)
    {

        $venteDetails = $this->em->getRepository(VenteDetail::class)
            ->createQueryBuilder('venteDetail')
            ->select()
            ->join('venteDetail.produit', 'produit')
            ->join('venteDetail.vente', 'mere')
            ->join('mere.pointDeVente', 'pointDeVente')
            ->where('pointDeVente.id=:pointDeVenteId')
            ->andWhere('mere.daty<=:dateNow')
            ->andWhere('mere.daty>=:dateRepport')
            ->andWhere('produit.id=:produitId')
            ->setParameters([
                "pointDeVenteId" => $produit->getPointDeVente()->getId(),
                "dateNow" => $dateFin,
                "dateRepport" => $dateDebut,
                "produitId" => $produit->getId()
            ])
            ->addOrderBy('mere.daty', 'desc')
            ->getQuery()
            ->getResult();

        return $venteDetails;
    }
}
