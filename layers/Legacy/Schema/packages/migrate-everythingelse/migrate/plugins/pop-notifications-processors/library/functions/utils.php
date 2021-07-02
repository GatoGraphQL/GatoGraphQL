<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class AAL_PoPProcessors_NotificationUtils
{
    public static function getNotificationcountId()
    {
        return HooksAPIFacade::getInstance()->applyFilters('AAL_PoPProcessors_NotificationUtils:notificationcount_id', 'notifications-count');
    }
}
