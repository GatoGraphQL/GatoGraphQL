<?php

class PoP_AddComments_Notifications_Utils
{
    public static function notifyUserForAllComments($user_id)
    {
        return \PoP\Root\App::getHookManager()->applyFilters(
            'PoP_AddComments_Notifications_Utils:notifyUserForAllComments',
            false,
            $user_id
        );
    }
}
