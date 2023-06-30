<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\NotificationMutations\MutationResolvers\MarkAsUnreadNotificationMutationResolver;

class MarkAsUnreadNotificationMutationResolverBridge extends AbstractMarkAsReadOrUnreadNotificationMutationResolverBridge
{
    private ?MarkAsUnreadNotificationMutationResolver $markAsUnreadNotificationMutationResolver = null;

    final public function setMarkAsUnreadNotificationMutationResolver(MarkAsUnreadNotificationMutationResolver $markAsUnreadNotificationMutationResolver): void
    {
        $this->markAsUnreadNotificationMutationResolver = $markAsUnreadNotificationMutationResolver;
    }
    final protected function getMarkAsUnreadNotificationMutationResolver(): MarkAsUnreadNotificationMutationResolver
    {
        if ($this->markAsUnreadNotificationMutationResolver === null) {
            /** @var MarkAsUnreadNotificationMutationResolver */
            $markAsUnreadNotificationMutationResolver = $this->instanceManager->getInstance(MarkAsUnreadNotificationMutationResolver::class);
            $this->markAsUnreadNotificationMutationResolver = $markAsUnreadNotificationMutationResolver;
        }
        return $this->markAsUnreadNotificationMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getMarkAsUnreadNotificationMutationResolver();
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }
}
