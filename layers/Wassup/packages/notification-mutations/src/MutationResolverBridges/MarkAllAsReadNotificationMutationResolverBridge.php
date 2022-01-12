<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolverBridges;

use PoP\Root\App;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
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

    public function getFormData(): array
    {
        $form_data = array(
            'user_id' => App::getState('current-user-id'),
        );

        return $form_data;
    }
}
