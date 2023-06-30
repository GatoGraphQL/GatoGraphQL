<?php

declare(strict_types=1);

namespace PoPSchema\Notifications\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPSchema\Notifications\RelationalTypeDataLoaders\ObjectType\NotificationObjectTypeDataLoader;

class NotificationObjectTypeResolver extends AbstractObjectTypeResolver
{
    private ?NotificationObjectTypeDataLoader $notificationObjectTypeDataLoader = null;
    
    final public function setNotificationObjectTypeDataLoader(NotificationObjectTypeDataLoader $notificationObjectTypeDataLoader): void
    {
        $this->notificationObjectTypeDataLoader = $notificationObjectTypeDataLoader;
    }
    final protected function getNotificationObjectTypeDataLoader(): NotificationObjectTypeDataLoader
    {
        if ($this->notificationObjectTypeDataLoader === null) {
            /** @var NotificationObjectTypeDataLoader */
            $notificationObjectTypeDataLoader = $this->instanceManager->getInstance(NotificationObjectTypeDataLoader::class);
            $this->notificationObjectTypeDataLoader = $notificationObjectTypeDataLoader;
        }
        return $this->notificationObjectTypeDataLoader;
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
        return $this->getNotificationObjectTypeDataLoader();
    }
}
