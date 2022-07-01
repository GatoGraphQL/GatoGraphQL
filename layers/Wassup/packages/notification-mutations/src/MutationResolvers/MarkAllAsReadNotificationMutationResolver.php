<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\MutationDataProviderInterface;
use PoP_Notifications_API;
use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class MarkAllAsReadNotificationMutationResolver extends AbstractMutationResolver
{
    protected function additionals(MutationDataProviderInterface $mutationDataProvider): void
    {
        App::doAction('GD_NotificationMarkAllAsRead:additionals', $mutationDataProvider);
    }

    protected function markAllAsRead(MutationDataProviderInterface $mutationDataProvider)
    {
        // return AAL_Main::instance()->api->setStatusMultipleNotifications($mutationDataProvider->get('user_id'), AAL_POP_STATUS_READ);
        return PoP_Notifications_API::setStatusMultipleNotifications($mutationDataProvider->get('user_id'), \AAL_POP_STATUS_READ);
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(MutationDataProviderInterface $mutationDataProvider): mixed
    {
        $hist_ids = $this->markAllAsRead($mutationDataProvider);
        $this->additionals($mutationDataProvider);

        return $hist_ids;
    }
}
