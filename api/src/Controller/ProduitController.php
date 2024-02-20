<?php

namespace App\Controller;

use App\Entity\ApprovisionnementDetail;
use App\Entity\Categorie;
use App\Entity\PrixUnite;
use App\Entity\Produit;
use App\Entity\QuantificationEquivalence;
use App\Entity\RepportANouveau;
use App\Service\ApprovisionnementDetailService;
use App\Service\ApprovisionnementService;
use App\Service\CategorieService;
use App\Service\ProduitService;
use App\Service\StockService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProduitController extends AbstractController
{


    public function __construct(
        private EntityManagerInterface $em,
        private Security $security,
        private SerializerInterface $serializer,
        private ProduitService $produitService,
        private CategorieService $categorieService,
        private StockService $stockService,
        private ApprovisionnementDetailService $approvisionnementDetailService,
        private ApprovisionnementService $approvisionnementService
    ) {
    }

    #[Route('/produits/statistiqueVente', methods: ['GET'])]
    public function statistiqueVente(Request $req)
    {
        try {
            $produitId = $req->query->get('produitId');
            $intervale = $req->query->get('intervale');
            $dateDebut = $req->query->get('dateDebut');
            $dateFin = $req->query->get('dateFin');
            $retour = $this->produitService->getStatistiqueVente($produitId, $intervale, $dateDebut, $dateFin);
            return new JsonResponse($retour);
        } catch (\Throwable $th) {
            return new JsonResponse($th->getMessage());
        }
    }

    #[Route('/produits/prixTotalVentes/{id}', methods: ['GET'])]
    public function prixTotalVentes($id)
    {
        try {
            $retour = $this->produitService->getPrixTotalVentes($id);
            return new JsonResponse($retour);
        } catch (\Throwable $th) {
            return new JsonResponse($th->getMessage(), 500);
        }
    }

    #[Route('/produits/classement', methods: ['GET'])]
    public function classementProduit(Request $req)
    {
        try {
            $pointDeVenteId = 0;
            $pointDeVenteId = $req->query->get('pointDeVenteId');
            if (!$pointDeVenteId) {
                throw new Exception("Undefined pointDeVenteId");
            }
            $retour = $this->produitService->getClassementProduitVente($pointDeVenteId);
            return new JsonResponse($retour);
        } catch (\Throwable $th) {
            return new JsonResponse($th->getMessage(), 500);
        }
    }

    #[Route('/produits/prix/{id}', methods: ['GET'])]
    public function getPrix($id)
    {
        try {
            $retour = $this->produitService->getPrix($id);
            return new JsonResponse($retour);
        } catch (\Throwable $th) {
            return new JsonResponse($th->getMessage(), 500);
        }
    }

    #[Route('/produits/currentRepport/{produitId}', methods: ['GET'])]
    public function currentRepport($produitId, Request $req)
    {
        $produit = $this->em->getRepository(Produit::class)->find($produitId);
        $dateStr = $req->query->get('date');

        $date = new DateTime();
        if (!empty($dateStr)) {
            $date = new DateTime($dateStr);
        }

        if (empty($produit)) {
            return new JsonResponse("Le produit n'existe pas", 500);
        }

        try {
            $retour = $this->produitService->getCurrentInventaire($produit, $date);

            if ($retour == null) {

                return new Response(null);
            }

            $normalized = $this->serializer->normalize($retour, null, ['groups' => ["repport:collection", "produit:collection", "quantification:collection"]]);

            return new JsonResponse($normalized);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), 500);
        }
    }

    #[Route('/produits/currentStocks/{produitId}', methods: ['GET'])]
    public function getCurrentStocks($produitId)
    {
        $produit = $this->em->getRepository(Produit::class)->find($produitId);
        if (empty($produit)) {
            return new JsonResponse("Le produit n'existe pas", 500);
        }
        $pointDeVente = $produit->getPointDeVente();
        if (empty($pointDeVente)) {
            return new JsonResponse("Le produit n'a pas de point de vente", 500);
        }

        $dateDebut = null;
        $inventaire = $this->stockService->getDernierRepportProduit($produitId, $pointDeVente->getId());
        if (!empty($inventaire)) {
            $dateDebut = $inventaire->getDaty();
        }


        $approvisionnementDetails = $this->approvisionnementService->getApprovisionnementDetailsProduit($produitId, $dateDebut);
        $normalized = $this->serializer->normalize($approvisionnementDetails, null, ['groups' => ['stockVente', 'quantification:collection']]);

        if (!empty($inventaire)) {
            $normalizedInventaire = $this->serializer->normalize($inventaire, null, ['groups' => ['stockVente', 'quantification:collection']]);
            array_unshift($normalized, $normalizedInventaire);
        }

        return new JsonResponse($normalized);
    }

    #[Route('/produits/currentNbVentes/{produitId}', methods: ['GET'])]
    public function getCurrentNbVentes($produitId)
    {
        $produit = $this->em->getRepository(Produit::class)->find($produitId);
        if (empty($produit)) {
            return new JsonResponse("Le produit n'existe pas", 500);
        }
        $pointDeVente = $produit->getPointDeVente();
        if (empty($pointDeVente)) {
            return new JsonResponse("Le produit n'a pas de point de vente", 500);
        }

        $dateDebut = null;
        $inventaire = $this->stockService->getDernierRepportProduit($produitId, $pointDeVente->getId());
        if (!empty($inventaire)) {
            $dateDebut = $inventaire->getDaty();
        }

        $retour = $this->approvisionnementService->getQuantiteVenteProduit($produitId, $dateDebut);

        return new JsonResponse($retour);
    }


    #[Route('/produits/approvisionnementsByRepport/{produitId}', methods: ['GET'])]
    public function approvisionnementsByRepport($produitId, Request $req)
    {
        $produit = $this->em->getRepository(Produit::class)->find($produitId);
        if (!$produit) {
            return new JsonResponse("Le produit n'existe pas", 500);
        }
        $dateStr = $req->query->get('date');

        $dateFin = new DateTime();
        $dateDebut = new DateTime('1970-1-1 00:00:00');
        if (!empty($dateStr)) {
            $dateFin = new DateTime($dateStr);
        }
        $repport = $this->produitService->getCurrentInventaire($produit, $dateFin);
        if ($repport) {
            $dateDebut = $repport->getDaty();
        }

        $approvisionnements = $this->produitService->getApprovisionnementDetailsParDate($produit, $dateDebut, $dateFin);
        $normalized = $this->serializer->normalize($approvisionnements, null, ['groups' => ['approvisionnementDetail:collection', 'approvisionnement:collection', 'fournisseur:collection', 'quantification:collection', 'produit:collection']]);

        return new JsonResponse($normalized);
    }

    #[Route('/produits/ventesByRepport/{produitId}', methods: ['GET'])]
    public function ventesByRepport($produitId, Request $req)
    {
        $produit = $this->em->getRepository(Produit::class)->find($produitId);
        if (!$produit) {
            return new JsonResponse("Le produit n'existe pas", 500);
        }
        $dateStr = $req->query->get('date');

        $dateFin = new DateTime();
        $dateDebut = new DateTime('1970-1-1 00:00:00');
        if (!empty($dateStr)) {
            $dateFin = new DateTime($dateStr);
        }
        $repport = $this->produitService->getCurrentInventaire($produit, $dateFin);
        if ($repport) {
            $dateDebut = $repport->getDaty();
        }

        $ventes = $this->produitService->getVenteDetailsParDate($produit, $dateDebut, $dateFin);
        $normalized = $this->serializer->normalize($ventes, null, ['groups' => [
            'venteDetail:collection',
            'vente:post',
            'quantification:collection',
            'produit:collection',
            'client:collection'
        ]]);

        return new JsonResponse($normalized);
    }

    #[Route('/produits/estimateStock/{produitId}', methods: ['GET'])]
    public function estimationStock($produitId)
    {
        try {
            $retour = $this->approvisionnementService->estimationStock($produitId);
            return new JsonResponse($retour);
        } catch (\Throwable $th) {
            return new JsonResponse($th->getMessage(), 500);
        }
    }

    // #[Route('/produits/estimateStock/{produitId}', methods: ['GET'])]
    // public function estimationStock($produitId)
    // {
    //     $utilisateur = $this->security->getUser();

    //     if ($utilisateur) {
    //         $produit = $this->em->getRepository(Produit::class)->find($produitId);
    //         if ($produit) {
    //             $dateNow = new DateTime();
    //             $pointDeVente = $utilisateur->getPointDeVente();
    //             // quantification defaut
    //             $quantificationDefaut = $produit->getQuantification();
    //             if (!$quantificationDefaut) {
    //                 return new JsonResponse("Undefined quantification defaut", 500);
    //             }

    //             if ($pointDeVente) {
    //                 // get repport à nouveau
    //                 $qb = $this->em->getRepository(RepportANouveau::class)->createQueryBuilder('repport');
    //                 $repports = $qb->select()
    //                     ->join('repport.produit', 'produit')
    //                     ->join('repport.pointDeVente', 'pointDeVente')
    //                     ->where('repport.daty < :dateNow')
    //                     ->andWhere('produit.id=:produitId')
    //                     ->andWhere('pointDeVente.id=:pointDeVenteId')
    //                     ->addOrderBy('repport.daty', 'desc')
    //                     ->setMaxResults(1)
    //                     ->setParameter('dateNow', $dateNow)
    //                     ->setParameter('produitId', $produitId)
    //                     ->setParameter('pointDeVenteId', $pointDeVente->getId())
    //                     ->getQuery()->getResult();


    //                 // voici la quantité initiale, puis je convertis
    //                 $quantiteInitial = !empty($repports[0]) ? $repports[0]->getQuantite() : 0;
    //                 $quantificationRepport = !empty($repports[0]) ? $repports[0]->getQuantification() : null;
    //                 $dateRepport = !empty($repports[0]) ? $repports[0]->getDaty() : new DateTime("1970-1-1 00:00:00");
    //                 // conversion de la quantité initial
    //                 if (!empty($repports[0])) {
    //                     if (!$quantificationRepport) {
    //                         return new JsonResponse("Quantification incorrecte pour le dernier repport à nouveau", 500);
    //                     }
    //                     // si la quantification repport != quantificationDefaut, je convertis
    //                     if ($quantificationRepport->getId() !== $quantificationDefaut->getId()) {
    //                         // conversion de la quantité initiale
    //                         // recherche d'une quantification équivalente
    //                         $Q_equivalences = $this->em->getRepository(QuantificationEquivalence::class)
    //                             ->createQueryBuilder('equivalence')
    //                             ->select()
    //                             ->join('equivalence.produit', 'produit')
    //                             ->join('equivalence.quantification', 'quantification')
    //                             ->where('produit.id=:produitId')
    //                             ->andWhere('quantification.id=:quantificationId')
    //                             ->setParameters([
    //                                 'produitId' => $produitId,
    //                                 'quantificationId' => $quantificationRepport->getId()
    //                             ])
    //                             ->getQuery()
    //                             ->getResult();

    //                         // Je vérifie que l'equivalence existe bien
    //                         // Si il existe plus de 1, je revoie "conflit"
    //                         if (count($Q_equivalences) > 1) {
    //                             return new JsonResponse("Conflit de quantificationEquivalence", 500);
    //                         }
    //                         if (empty($Q_equivalences[0])) {
    //                             $normalized = $this->serializer->normalize($quantificationRepport);
    //                             $responseBody = ["message" => "Aucune quantification dans le produit ne correspond au repport à nouveau, veuillez renseigner ’" . $quantificationRepport->getNom() . "’", "objet" => $normalized];
    //                             return new JsonResponse($responseBody, 500);
    //                         }
    //                         $equivalence = $Q_equivalences[0];
    //                         if (!$equivalence->getValeur()) {
    //                             $responseBody = [
    //                                 "message" => "Veuillez reseigner la valeur de ‘" . $quantificationRepport->getNom() . "‘ dans le produit"
    //                             ];
    //                             return new JsonResponse($responseBody, 500);
    //                         }

    //                         //conversion amzay àry
    //                         $quantiteInitial /= $equivalence->getValeur();
    //                     }
    //                 }

    //                 // les approvisionnements
    //                 $quantiteApprovisionnement = 0;
    //                 try {
    //                     $quantiteApprovisionnement = $this->produitService->getQuantiteApprovisionnement($produit, $dateRepport, $dateNow, $pointDeVente->getId());
    //                 } catch (Exception $e) {
    //                     return new JsonResponse($e->getMessage(), $e->getCode());
    //                 }
    //                 // fin approvisionnemnts

    //                 // les ventes
    //                 $quantiteVentes = 0;
    //                 try {
    //                     $quantiteVentes = $this->produitService->getQuantiteVente($produit, $dateRepport, $dateNow, $pointDeVente->getId());
    //                 } catch (Exception $e) {
    //                     return new JsonResponse($e->getMessage(), $e->getCode());
    //                 }

    //                 // fin ventes

    //                 return new JsonResponse($quantiteInitial + $quantiteApprovisionnement - $quantiteVentes);
    //             } else {
    //                 return new JsonResponse("Point de vente indéfini", 401);
    //             }
    //         } else {
    //             return new JsonResponse("Produit inexistant", 400);
    //         }
    //     } else {
    //         return new JsonResponse("Utilisateur indéfini", 401);
    //     }
    // }

    #[Route('/produits/uploadImage', name: 'uploadProduitImage', methods: ['POST'])]
    public function uploadImage(Request $request)
    {
        // Récupérez le fichier image depuis la requête
        $imageFile = $request->files->get('photo');

        // Gérez le téléchargement de l'image vers un répertoire
        $imageFileName = md5(uniqid()) . '.' . $imageFile->guessExtension();

        $imageFile->move(
            $this->getParameter('photo_directory'), // Répertoire de destination (configurez cela dans config/services.yaml)
            $imageFileName
        );

        // Traitez l'image comme vous le souhaitez (par exemple, enregistrez le nom du fichier dans une base de données)

        return $this->json(['message' => 'Image téléchargée avec succès', 'fileName' => $imageFileName]);
    }

    #[Route('/getProduitQuantifications/{produitId}', name: 'getProduitQuantifications', methods: ['GET'])]
    public function getProduitQuantifications(SerializerInterface $serializer, $produitId, Request $request)
    {
        $retour = [];
        $entite = $this->em->getRepository(Produit::class)->find($produitId);

        // var_dump($entite);
        $quantificationDefaut = $entite->getQuantification();
        if ($quantificationDefaut) {
            array_push($retour, $quantificationDefaut);

            // rechercher les quantificationsEquivalences
            $queryBuilder = $this->em->createQueryBuilder()
                ->select('e')
                ->from('App\Entity\QuantificationEquivalence', 'e')
                ->join('e.produit', 'pr')
                ->where('pr.id = :produitId')
                ->setParameter('produitId', $produitId);

            $equivalences = $queryBuilder->getQuery()->getResult();
            foreach ($equivalences as $singleEquivalence) {
                array_push($retour, $singleEquivalence->getQuantification());
            }
        }

        // appliquer le filtre de nom
        $retourFiltrer = [];
        $nom = $request->query->get('nom');
        if ($nom != null || true) {
            foreach ($retour as $ligne) {
                if (stripos($ligne->getNom(), $nom) !== false) {
                    $retourFiltrer[] = $ligne;
                }
            }
        }

        // restructurer le tableau
        $retour = array("hydra:member" => $retourFiltrer);

        // $normalized = $serializer->normalize($entite, null, ['groups' => 'user:collection']);
        $normalized = $serializer->normalize($retour);

        return $this->json($normalized);
    }

    #[Route('/produits/prixAuto/{produitId}', methods: ["POST"])]
    public function prixAuto($produitId)
    {
        $produit = $this->em->getRepository(Produit::class)->find($produitId);

        if ($produit) {

            // try {
            $prix = $produit->getPrix();
            $connexion = $this->em->getConnection();
            $sql = "update quantification_equivalence set prix=:prix/valeur where produit_id=:produit_id";

            $stmt = $connexion->prepare($sql);
            $stmt->bindValue("prix", $prix);
            $stmt->bindValue("produit_id", $produitId);
            $stmt->executeQuery();

            // historique
            $queryBuilder = $this->em->createQueryBuilder()
                ->select('e')
                ->from('App\Entity\QuantificationEquivalence', 'e')
                ->join('e.produit', 'pr')
                ->where('pr.id = :produitId')
                ->setParameter('produitId', $produitId);
            $quantifEquivalences = $queryBuilder->getQuery()->getResult();
            foreach ($quantifEquivalences as $element) {
                $prixUnite = new PrixUnite();
                $prixUnite->setProduit($produit);
                $prixUnite->setQuantification($element->getQuantification());
                $prixUnite->setValeur($element->getPrix());
                $this->em->persist($prixUnite);
                $this->em->flush();
            }

            return new JsonResponse(["message" => "Ajustement de prix réussi"], 200);
            // } catch (\Throwable $th) {
            //     // throw $th;
            //     return new JsonResponse(["message" => "erreur serveur"], 500);
            // }
        } else {
            return new JsonResponse(["message" => "produit inexistant"], 400);
        }
    }

    #[Route('/produits/addCategorie/{produitId}', methods: ['POST'])]
    public function ajouterProduitCategorie($produitId, Request $request)
    {
        // supprimer les catégories délaissées
        try {
            $this->produitService->deleteUnusedCategory();
        } catch (\Throwable $th) {
        }
        try {
            $body = $request->getContent();
            $categorie = $this->serializer->deserialize($body, Categorie::class, "json");

            $nom = $categorie->getNom();
            if (empty(trim($nom))) {
                return new JsonResponse("Le nom ne peut pas être vide", 400);
            }
            $savedCategorie = $this->categorieService->findOrCreate($nom);

            $produit = $this->em->getRepository(Produit::class)->find($produitId);

            $produit->addCategorie($savedCategorie);
            $this->em->persist($produit);
            $this->em->flush();

            $normalized = $this->serializer->normalize($produit, null, ["groups" => ['produit:collection', 'categorie:collection', 'quantification:collection']]);

            return new JsonResponse($normalized);
        } catch (\Throwable $th) {
            return new JsonResponse($th->getMessage(), 500);
        }
    }


    #[Route('/prixUnites/{produitId}', name: 'getPrixUnites', methods: ['GET'])]
    public function getPrixUnites($produitId, SerializerInterface $serializer)
    {
        $retour = [];

        // Je commence par récup le prix par défaut
        $produit = $this->em->getRepository(Produit::class)->find($produitId);
        $retour[] = array("id" => 0, "quantification" => $produit->getQuantification(), "prix" => $produit->getPrix());

        // Après, je récup le prix des equivalences
        $queryBuilder = $this->em->createQueryBuilder()
            ->select('e')
            ->from('App\Entity\QuantificationEquivalence', 'e')
            ->join('e.produit', 'pr')
            ->where('pr.id = :produitId')
            ->setParameter('produitId', $produitId);

        $quantifEquivalences = $queryBuilder->getQuery()->getResult();
        foreach ($quantifEquivalences as $element) {
            $retour[] = array("id" => $element->getId(), "quantification" => $element->getQuantification(), "prix" => $element->getPrix());
        }

        // restructuration
        $retour = array("hydra:member" => $retour);

        $normalized = $serializer->normalize($retour);
        return $this->json($normalized);
    }

    #[Route('/produits/deleteQuantificationDefaut', methods: ['POST'])]
    public function supprimerQuantificationDefaut(Request $request)
    {
        $body = $request->getContent();
        $id = json_decode($body)->id;
        if (isset($id) && $id > 0) {
            $produit = $this->em->getRepository(Produit::class)->find($id);
            if (!$produit) {
                return new JsonResponse("Produit inexistant", 500);
            }

            $produit->setQuantification(null);
            $this->em->persist($produit);

            $equivalences = $produit->getQuantificationEquivalences();
            foreach ($equivalences as $element) {
                $element->setValeur(null);
                $this->em->persist($element);
            }

            $this->em->flush();
            return new JsonResponse("Modifications effectuées", 200);
        } else {
            return new JsonResponse("Produit id incorrect", 400);
        }
    }
}
