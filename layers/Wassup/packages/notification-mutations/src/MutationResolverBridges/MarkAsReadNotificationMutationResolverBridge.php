<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\NotificationMutations\MutationResolvers\MarkAsReadNotificationMutationResolver;

class MarkAsReadNotificationMutationResolverBridge extends AbstractMarkAsReadOrUnreadNotificationMutationResolverBridge
{
    private ?MarkAsReadNotificationMutationResolver $markAsReadNotificationMutationResolver = null;

    final public function setMarkAsReadNotificationMutationResolver(MarkAsReadNotificationMutationResolver $markAsReadNotificationMutationResolver): void
    {
        $this->markAsReadNotificationMutationResolver = $markAsReadNotificationMutationResolver;
    }
    final protected function getMarkAsReadNotificationMutationResolver(): MarkAsReadNotificationMutationResolver
    {
        if ($this->markAsReadNotificationMutationResolver === null) {
            /** @var MarkAsReadNotificationMutationResolver */
            $markAsReadNotificationMutationResolver = $this->instanceManager->getInstance(MarkAsReadNotificationMutationResolver::class);
            $this->markAsReadNotificationMutationResolver = $markAsReadNotificationMutationResolver;
        }
        return $this->markAsReadNotificationMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getMarkAsReadNotificationMutationResolver();
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }
}
