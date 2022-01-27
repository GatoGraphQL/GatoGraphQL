<?php

class AAL_PoPProcessors_NotificationUtils
{
    public static function getNotificationcountId()
    {
        return \PoP\Root\App::applyFilters('AAL_PoPProcessors_NotificationUtils:notificationcount_id', 'notifications-count');
    }
}
