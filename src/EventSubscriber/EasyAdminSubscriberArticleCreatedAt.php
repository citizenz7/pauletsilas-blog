<?php

namespace App\EventSubscriber;

use App\Entity\Article;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class EasyAdminSubscriberArticleCreatedAt implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['setArticleCreatedAt']
        ];
    }

    public function setArticleCreatedAt(BeforeEntityPersistedEvent $event): void
    {
        $article = $event->getEntityInstance();

        if (!$article instanceof Article) {
            return;
        }

        $article->setPostedAt(new \DateTime());
        $article->setViews(1);
    }
}