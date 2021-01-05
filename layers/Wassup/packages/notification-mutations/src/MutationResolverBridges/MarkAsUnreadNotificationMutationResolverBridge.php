<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolverBridges;

class MarkAsUnreadNotificationMutationResolverBridge extends AbstractMarkAsReadOrUnreadNotificationMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return MarkAsUnreadNotificationMutationResolver::class;
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }
}
