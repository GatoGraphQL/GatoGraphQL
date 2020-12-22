<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\State\ApplicationState;
use PoPSitesWassup\NotificationMutations\MutationResolvers\MarkAllAsReadNotificationMutationResolver;

class MarkAllAsReadNotificationMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return MarkAllAsReadNotificationMutationResolver::class;
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getFormData(): array
    {
        $vars = ApplicationState::getVars();
        $form_data = array(
            'user_id' => $vars['global-userstate']['current-user-id'],
        );

        return $form_data;
    }
}

