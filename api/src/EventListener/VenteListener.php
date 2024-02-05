<?php

namespace App\EventListener;

use App\Entity\Vente;
use App\Service\StockService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[AsEntityListener(event: Events::postRemove, method: 'postDelete', entity: Vente::class)]
class VenteListener
{
    public function __construct(private StockService $stockService, private EntityManagerInterface $em)
    {
    }

    public function postDelete(Vente $entite, PostRemoveEventArgs $args)
    {
        print "Une vente a été supprimé";
        // dd($entite);

    }
}
