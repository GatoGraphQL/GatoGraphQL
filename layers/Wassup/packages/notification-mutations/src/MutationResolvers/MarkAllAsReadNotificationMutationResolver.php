<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class MarkAllAsReadNotificationMutationResolver extends AbstractMutationResolver
{
    protected function additionals($form_data): void
    {
        \PoP\Root\App::getHookManager()->doAction('GD_NotificationMarkAllAsRead:additionals', $form_data);
    }

    protected function markAllAsRead($form_data)
    {
        // return AAL_Main::instance()->api->setStatusMultipleNotifications($form_data['user_id'], AAL_POP_STATUS_READ);
        return \PoP_Notifications_API::setStatusMultipleNotifications($form_data['user_id'], \AAL_POP_STATUS_READ);
    }

    public function executeMutation(array $form_data): mixed
    {
        $hist_ids = $this->markAllAsRead($form_data);
        $this->additionals($form_data);

        return $hist_ids;
    }
}
