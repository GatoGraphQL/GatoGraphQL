<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoPSchema\Users\Facades\UserTypeAPIFacade;
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// By not expending from class AAL_Hook_Base, this code is de-attached from AAL
class WSL_AAL_PoP_Hook_Users /* extends AAL_Hook_Base*/
{
    public function __construct()
    {

        // User welcome message (function implemented already, but must connect it with the hook)
        HooksAPIFacade::getInstance()->addAction(
            'popcomponent:sociallogin:usercreated',
            array(PoP_Notifications_UserPlatform_Utils::class, 'welcomeMessage')
        );

        // Prompt the user to change the email
        HooksAPIFacade::getInstance()->addAction(
            'popcomponent:sociallogin:usercreated',
            array($this, 'requestChangeEmail'),
            20, // Execute after the User Welcome Message
            2
        );

        // parent::__construct();
    }

    public function requestChangeEmail($user_id, $provider)
    {
        if (!defined('POP_NOTIFICATIONS_USERPLACEHOLDER_SYSTEMNOTIFICATIONS') || !POP_NOTIFICATIONS_USERPLACEHOLDER_SYSTEMNOTIFICATIONS || !defined('POP_USERPLATFORM_ROUTE_EDITPROFILE') || !POP_USERPLATFORM_ROUTE_EDITPROFILE) {
            return;
        }

        // Twitter's provided user email is a fake one (eg: user_test@example.com), so prompt the user to fix it
        $fake_email_providers = array("Twitter", "Identica", "Tumblr", "Goodreads", "500px", "Vkontakte", "Gowalla", "Steam");
        if (!in_array($provider, $fake_email_providers)) {
            return;
        }

        $userTypeAPI = UserTypeAPIFacade::getInstance();
        PoP_Notifications_Utils::insertLog(
            array(
                'action'      => WSL_AAL_POP_ACTION_USER_REQUESTCHANGEEMAIL,
                'object_type' => 'User',
                'user_id'     => POP_NOTIFICATIONS_USERPLACEHOLDER_SYSTEMNOTIFICATIONS,
                'object_id'   => $user_id,
                'object_name' => $userTypeAPI->getUserDisplayName($user_id),
            )
        );
    }
}
