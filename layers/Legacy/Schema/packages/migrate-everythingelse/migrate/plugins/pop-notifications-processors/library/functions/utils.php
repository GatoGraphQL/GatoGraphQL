<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class AAL_PoPProcessors_NotificationUtils
{
    public static function getNotificationcountId()
    {
        return \PoP\Root\App::getHookManager()->applyFilters('AAL_PoPProcessors_NotificationUtils:notificationcount_id', 'notifications-count');
    }
}
