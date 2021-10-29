<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\NotificationMutations\MutationResolvers\MarkAsUnreadNotificationMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class MarkAsUnreadNotificationMutationResolverBridge extends AbstractMarkAsReadOrUnreadNotificationMutationResolverBridge
{
    private ?MarkAsUnreadNotificationMutationResolver $markAsUnreadNotificationMutationResolver = null;

    public function setMarkAsUnreadNotificationMutationResolver(MarkAsUnreadNotificationMutationResolver $markAsUnreadNotificationMutationResolver): void
    {
        $this->markAsUnreadNotificationMutationResolver = $markAsUnreadNotificationMutationResolver;
    }
    protected function getMarkAsUnreadNotificationMutationResolver(): MarkAsUnreadNotificationMutationResolver
    {
        return $this->markAsUnreadNotificationMutationResolver ??= $this->instanceManager->getInstance(MarkAsUnreadNotificationMutationResolver::class);
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
