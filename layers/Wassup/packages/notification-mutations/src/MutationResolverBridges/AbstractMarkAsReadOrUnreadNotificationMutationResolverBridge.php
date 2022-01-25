<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolverBridges;

use PoP\Root\App;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;

abstract class AbstractMarkAsReadOrUnreadNotificationMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    public function getFormData(): array
    {
        $form_data = array(
            'histid' => \PoP\Root\App::query($this->getRequestKey()),
            'user_id' => App::getState('current-user-id'),
        );

        return $form_data;
    }

    protected function getRequestKey()
    {
        return 'nid';
    }
}
