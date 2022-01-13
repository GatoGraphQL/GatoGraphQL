<?php
use PoP\ComponentModel\ComponentInfo as ComponentModelComponentInfo;
use PoP\ComponentModel\State\ApplicationState;

class PoP_ModuleManager_UserMetaUtils
{
    public static function init(): void
    {
        \PoP\Root\App::addAction(
            'popcms:shutdown',
            array('PoP_ModuleManager_UserMetaUtils', 'saveUserMeta')
        );
    }

    public static function saveUserMeta()
    {

        // Function used to save information on the user access. In particular, we need the last access time, for the Notifications
        // Can do it only if the page is mutableonrequestdata
        if (PoP_UserState_Utils::currentRouteRequiresUserState()) {
            if (\PoP\Root\App::getState('is-user-logged-in')) {
                PoP_UserPlatform_UserUtils::saveUserLastAccess(\PoP\Root\App::getState('current-user-id'), ComponentModelComponentInfo::get('time'));
            }
        }
    }
}

/**
 * Initialization
 */
PoP_ModuleManager_UserMetaUtils::init();
