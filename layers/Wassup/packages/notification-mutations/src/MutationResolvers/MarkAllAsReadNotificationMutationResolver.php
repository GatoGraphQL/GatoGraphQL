<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolvers;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP_Notifications_API;
use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class MarkAllAsReadNotificationMutationResolver extends AbstractMutationResolver
{
    protected function additionals(FieldDataAccessorInterface $fieldDataAccessor): void
    {
        App::doAction('GD_NotificationMarkAllAsRead:additionals', $fieldDataAccessor);
    }

    protected function markAllAsRead(FieldDataAccessorInterface $fieldDataAccessor)
    {
        // return AAL_Main::instance()->api->setStatusMultipleNotifications($fieldDataAccessor->getValue('user_id'), AAL_POP_STATUS_READ);
        return PoP_Notifications_API::setStatusMultipleNotifications($fieldDataAccessor->getValue('user_id'), \AAL_POP_STATUS_READ);
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(FieldDataAccessorInterface $fieldDataAccessor): mixed
    {
        $hist_ids = $this->markAllAsRead($fieldDataAccessor);
        $this->additionals($fieldDataAccessor);

        return $hist_ids;
    }
}
