<?php

declare(strict_types=1);

namespace PoPSchema\Notifications\TypeResolvers\Object;

use PoP\ComponentModel\TypeResolvers\Object\AbstractObjectTypeResolver;
use PoPSchema\Notifications\RelationalTypeDataLoaders\NotificationTypeDataLoader;

class NotificationTypeResolver extends AbstractObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'Notification';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Notifications for the user', 'notifications');
    }

    public function getID(object $resultItem): string | int | null
    {
        $notification = $resultItem;
        return $notification->histid;
    }

    public function getRelationalTypeDataLoaderClass(): string
    {
        return NotificationTypeDataLoader::class;
    }
}
