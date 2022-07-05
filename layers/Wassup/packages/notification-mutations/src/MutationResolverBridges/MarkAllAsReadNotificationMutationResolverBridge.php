<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\Root\App;
use PoPSitesWassup\NotificationMutations\MutationResolvers\MarkAllAsReadNotificationMutationResolver;

class MarkAllAsReadNotificationMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    private ?MarkAllAsReadNotificationMutationResolver $markAllAsReadNotificationMutationResolver = null;

    final public function setMarkAllAsReadNotificationMutationResolver(MarkAllAsReadNotificationMutationResolver $markAllAsReadNotificationMutationResolver): void
    {
        $this->markAllAsReadNotificationMutationResolver = $markAllAsReadNotificationMutationResolver;
    }
    final protected function getMarkAllAsReadNotificationMutationResolver(): MarkAllAsReadNotificationMutationResolver
    {
        return $this->markAllAsReadNotificationMutationResolver ??= $this->instanceManager->getInstance(MarkAllAsReadNotificationMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getMarkAllAsReadNotificationMutationResolver();
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    /**
     * @param array<string,mixed> $mutationData
     */
    public function addMutationDataForFieldDataAccessor(array &$mutationData): void
    {
        $mutationData['user_id'] = App::getState('current-user-id');
    }
}
