<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoPSitesWassup\NotificationMutations\MutationResolvers\MarkAllAsReadNotificationMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class MarkAllAsReadNotificationMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    protected MarkAllAsReadNotificationMutationResolver $markAllAsReadNotificationMutationResolver;

    #[Required]
    final public function autowireMarkAllAsReadNotificationMutationResolverBridge(
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
