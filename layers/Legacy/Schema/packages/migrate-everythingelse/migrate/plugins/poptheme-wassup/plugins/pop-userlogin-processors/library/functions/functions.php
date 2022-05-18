<?php

/**
 * Uniqueblocks
 */
\PoP\Root\App::addFilter('RequestUtils:getFramecomponentModules', 'getUserloginFramecomponentModules');
function getUserloginFramecomponentComponentVariations($componentVariations)
{

    // // If the page is not cacheable, then we can already get the state of the logged in user
    // // Otherwise, this info will come from calling page LOGGEDINUSER_DATA from the webplatform
    // if (PoP_UserState_Utils::currentRouteRequiresUserState()) {
    $componentVariations[] = [PoP_Module_Processor_UserAccountGroups::class, PoP_Module_Processor_UserAccountGroups::MODULE_GROUP_LOGGEDINUSERDATA];
    // }
    return $componentVariations;
}
