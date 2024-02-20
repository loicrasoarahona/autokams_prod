<?php

use ApiPlatform\Symfony\EventListener\EventPriorities;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class ProduitListener implements EventSubscriberInterface
{
    public function __construct(private EntityManager $em)
    {
    }

    public static function getSubscribedEvents()
    {
        dd("hey");
        return [
            ["api_platform.core.collection.pre_get"] => ['preGetCollection', EventPriorities::PRE_READ]
        ];
    }

    public function preGetCollection(ViewEvent $event)
    {
        dd("de aona e");
    }
}
