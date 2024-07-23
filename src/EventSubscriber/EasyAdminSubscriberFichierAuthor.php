<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Entity\Fichier;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EasyAdminSubscriberFichierAuthor implements EventSubscriberInterface
{
    public function __construct(private TokenStorageInterface $tokenStorage)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['setFichierAuthor']
        ];
    }

    public function setFichierAuthor(BeforeEntityPersistedEvent $event): void
    {
        $fichier = $event->getEntityInstance();

        if (!$fichier instanceof Fichier) {
            return;
        }

        $user = $this->tokenStorage->getToken()?->getUser();
        if (!$user instanceof User) {
            return;
        }

        $fichier->setAuthor($user);
    }
}