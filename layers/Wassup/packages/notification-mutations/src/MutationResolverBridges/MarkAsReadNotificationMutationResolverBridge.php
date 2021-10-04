<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\NotificationMutations\MutationResolvers\MarkAsReadNotificationMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class MarkAsReadNotificationMutationResolverBridge extends AbstractMarkAsReadOrUnreadNotificationMutationResolverBridge
{
    protected MarkAsReadNotificationMutationResolver $markAsReadNotificationMutationResolver;

    #[Required]
    final public function autowireMarkAsReadNotificationMutationResolverBridge(
        MarkAsReadNotificationMutationResolver $markAsReadNotificationMutationResolver,
    ): void {
        $this->markAsReadNotificationMutationResolver = $markAsReadNotificationMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->markAsReadNotificationMutationResolver;
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }
}
