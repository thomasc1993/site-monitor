<?php

namespace App\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use App\Entity\Site;
use App\Utils\Slugger;

final class UniqueSlug
{
    private $slugger;

    public function __construct(Slugger $slugger)
    {
        $this->slugger = $slugger;
    }

    public function PrePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Site)
        {
            return;
        }

        $entityManager = $args->getEntityManager();
        $entityName = $entityManager->getClassMetadata(Site::class)->getName();
        $slug = $this->slugger->generateUniqueSlug($entity->getName(), $entityName, $entity->getId());
        $entity->setSlug($slug);

        $entityManager->flush();
    }

    public function PreUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Site)
        {
            return;
        }

        $entityManager = $args->getEntityManager();
        $entityName = $entityManager->getClassMetadata(Site::class)->getName();
        $slug = $this->slugger->generateUniqueSlug($entity->getName(), $entityName, $entity->getId());
        $entity->setSlug($slug);
    }
}