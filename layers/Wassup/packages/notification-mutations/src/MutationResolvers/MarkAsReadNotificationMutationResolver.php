<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolvers;

class MarkAsReadNotificationMutationResolver extends AbstractMarkAsReadOrUnreadNotificationMutationResolver
{
    protected function getStatus()
    {
        return \AAL_POP_STATUS_READ;
    }
}
