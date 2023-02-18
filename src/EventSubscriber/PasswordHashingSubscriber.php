<?php

namespace App\EventSubscriber;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHashingSubscriber implements EventSubscriberInterface
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    { }

    public function onPasswordHashing(BeforeEntityPersistedEvent|BeforeEntityUpdatedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if ($entity instanceof User){
            if ($entity->getPlainPassword() && $entity->getPlainPassword() != ''){
                $entity->setPassword(
                    $this->hasher->hashPassword($entity, $entity->getPlainPassword())
                );
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => 'onPasswordHashing',
            BeforeEntityUpdatedEvent::class => 'onPasswordHashing',
        ];
    }
}
