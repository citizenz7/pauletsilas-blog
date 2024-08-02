<?php

namespace App\EventListener;

use App\Entity\Article;
use App\Service\EmailService;
use Doctrine\ORM\Event\PostUpdateEventArgs;

class ArticleActivationListener
{
    public function __construct(
        private readonly EmailService $emailService
    ) {}

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Article) {
            return;
        }

        if ($entity->isActive()) {
            $this->emailService->sendEmailUserArticleActivated($entity);
        }
    }
}
