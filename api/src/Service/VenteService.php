<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class VenteService
{

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function nextNumFacture()
    {
        $query = $this->entityManager->createQuery("select max(vente.numFacture) from App\Entity\Vente vente");
        $result = $query->getSingleScalarResult();
        if ($result == null) {
            $result = 0;
        }
        return  $result + 1;
    }
}
