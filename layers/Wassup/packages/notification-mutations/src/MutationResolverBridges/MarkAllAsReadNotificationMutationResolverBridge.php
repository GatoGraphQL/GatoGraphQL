<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\State\ApplicationState;
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
        $vars = ApplicationState::getVars();
        $form_data = array(
            'user_id' => $vars['current-user-id'],
        );

        return $form_data;
    }
}
