<?php

use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

class PoP_Notifications_UserPlatform_Utils
{
    public static function welcomeMessage($user_id)
    {

        // Enable only if the System User and the Welcome Message Post have been defined
        if (!POP_NOTIFICATIONS_USERPLACEHOLDER_SYSTEMNOTIFICATIONS || !POP_NOTIFICATIONS_URLPLACEHOLDER_USERWELCOME) {
            return;
        }

        $userTypeAPI = UserTypeAPIFacade::getInstance();
        PoP_Notifications_Utils::insertLog(
            array(
                'action'      => AAL_POP_ACTION_USER_WELCOMENEWUSER,
                'object_type' => 'User',
                'user_id'     => POP_NOTIFICATIONS_USERPLACEHOLDER_SYSTEMNOTIFICATIONS,
                'object_id'   => $user_id,
                'object_name' => $userTypeAPI->getUserDisplayName($user_id),
            )
        );
    }
}
