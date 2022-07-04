<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataAccessorInterface;
use PoP_Notifications_API;
use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class MarkAllAsReadNotificationMutationResolver extends AbstractMutationResolver
{
    protected function additionals(FieldDataAccessorInterface $fieldDataProvider): void
    {
        App::doAction('GD_NotificationMarkAllAsRead:additionals', $fieldDataProvider);
    }

    protected function markAllAsRead(FieldDataAccessorInterface $fieldDataProvider)
    {
        // return AAL_Main::instance()->api->setStatusMultipleNotifications($fieldDataProvider->get('user_id'), AAL_POP_STATUS_READ);
        return PoP_Notifications_API::setStatusMultipleNotifications($fieldDataProvider->get('user_id'), \AAL_POP_STATUS_READ);
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(FieldDataAccessorInterface $fieldDataProvider): mixed
    {
        $hist_ids = $this->markAllAsRead($fieldDataProvider);
        $this->additionals($fieldDataProvider);

        return $hist_ids;
    }
}
