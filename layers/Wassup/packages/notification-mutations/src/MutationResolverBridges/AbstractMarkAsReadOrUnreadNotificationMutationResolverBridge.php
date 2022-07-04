<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\Root\App;

abstract class AbstractMarkAsReadOrUnreadNotificationMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    public function appendMutationDataToFieldDataAccessor(\PoP\ComponentModel\Mutation\FieldDataAccessorInterface $fieldDataProvider): void
    {
        $fieldDataProvider->add('histid', App::query($this->getRequestKey()));
        $fieldDataProvider->add('user_id', App::getState('current-user-id'));
    }

    protected function getRequestKey()
    {
        return 'nid';
    }
}
