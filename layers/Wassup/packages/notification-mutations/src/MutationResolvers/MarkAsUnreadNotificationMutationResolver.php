<?php

declare(strict_types=1);

namespace PoPSitesWassup\NotificationMutations\MutationResolvers;

class MarkAsUnreadNotificationMutationResolver extends AbstractMarkAsReadOrUnreadNotificationMutationResolver
{
    protected function getStatus()
    {
        // null is also "Mark as Unread"
        return null;
    }
}
