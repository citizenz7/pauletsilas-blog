<?php

namespace App\EventListener;

use App\Entity\Article;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostUpdateEventArgs;

class ArticleActivationListener
{
    public function __construct(
        private readonly EmailService $emailService,
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Article) {
            return;
        }

        // Récupérer les modifications de l'entité
        $uow = $this->entityManager->getUnitOfWork();
        $changes = $uow->getEntityChangeSet($entity);

        // Vérifier si le champ 'active' a changé de false à true
        if (isset($changes['active']) && $changes['active'][0] === false && $changes['active'][1] === true) {
            $this->emailService->sendEmailUserArticleActivated($entity);
        }
    }
}
