<?php

declare(strict_types=1);

namespace PoPSchema\Notifications\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPSchema\Notifications\RelationalTypeDataLoaders\ObjectType\NotificationTypeDataLoader;

class NotificationObjectTypeResolver extends AbstractObjectTypeResolver
{
    private ?NotificationTypeDataLoader $notificationTypeDataLoader = null;
    
    final public function setNotificationTypeDataLoader(NotificationTypeDataLoader $notificationTypeDataLoader): void
    {
        $this->notificationTypeDataLoader = $notificationTypeDataLoader;
    }
    final protected function getNotificationTypeDataLoader(): NotificationTypeDataLoader
    {
        return $this->notificationTypeDataLoader ??= $this->instanceManager->getInstance(NotificationTypeDataLoader::class);
    }
    
    public function getTypeName(): string
    {
        return 'Notification';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Notifications for the user', 'notifications');
    }

    public function getID(object $object): string|int|null
    {
        $notification = $object;
        return $notification->histid;
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getNotificationTypeDataLoader();
    }
}
