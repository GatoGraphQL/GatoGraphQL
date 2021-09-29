<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolverBridges;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\State\ApplicationState;
use PoPSitesWassup\NotificationMutations\MutationResolvers\MarkAllAsReadNotificationMutationResolver;

class MarkAllAsReadNotificationMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    protected MarkAllAsReadNotificationMutationResolver $markAllAsReadNotificationMutationResolver;

    #[Required]
    public function autowireMarkAllAsReadNotificationMutationResolverBridge(
        MarkAllAsReadNotificationMutationResolver $markAllAsReadNotificationMutationResolver,
    ): void {
        $this->markAllAsReadNotificationMutationResolver = $markAllAsReadNotificationMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->markAllAsReadNotificationMutationResolver;
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
