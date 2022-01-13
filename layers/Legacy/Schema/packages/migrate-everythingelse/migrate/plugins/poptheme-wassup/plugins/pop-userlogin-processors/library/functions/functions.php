<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * Uniqueblocks
 */
\PoP\Root\App::getHookManager()->addFilter('RequestUtils:getFramecomponentModules', 'getUserloginFramecomponentModules');
function getUserloginFramecomponentModules($modules)
{

    // // If the page is not cacheable, then we can already get the state of the logged in user
    // // Otherwise, this info will come from calling page LOGGEDINUSER_DATA from the webplatform
    // if (PoP_UserState_Utils::currentRouteRequiresUserState()) {
    $modules[] = [PoP_Module_Processor_UserAccountGroups::class, PoP_Module_Processor_UserAccountGroups::MODULE_GROUP_LOGGEDINUSERDATA];
    // }
    return $modules;
}
