<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolverBridges;

use PoPSitesWassup\NotificationMutations\MutationResolvers\MarkAsReadNotificationMutationResolver;

class MarkAsReadNotificationMutationResolverBridge extends AbstractMarkAsReadOrUnreadNotificationMutationResolverBridge
{
    public function getMutationResolver(): \PoP\ComponentModel\MutationResolvers\MutationResolverInterface
    {
        return MarkAsReadNotificationMutationResolver::class;
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }
}
