<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\State\ApplicationState;

abstract class AbstractMarkAsReadOrUnreadNotificationMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    public function getFormData(): array
    {
        $vars = ApplicationState::getVars();
        $form_data = array(
            'histid' => $_REQUEST[$this->getRequestKey()] ?? null,
            'user_id' => $vars['global-userstate']['current-user-id'],
        );

        return $form_data;
    }

    protected function getRequestKey()
    {
        return 'nid';
    }
}

