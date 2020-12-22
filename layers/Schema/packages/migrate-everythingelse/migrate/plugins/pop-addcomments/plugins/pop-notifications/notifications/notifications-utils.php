<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_AddComments_Notifications_Utils
{
    public static function notifyUserForAllComments($user_id)
    {
        return HooksAPIFacade::getInstance()->applyFilters(
            'PoP_AddComments_Notifications_Utils:notifyUserForAllComments',
            false,
            $user_id
        );
    }
}
