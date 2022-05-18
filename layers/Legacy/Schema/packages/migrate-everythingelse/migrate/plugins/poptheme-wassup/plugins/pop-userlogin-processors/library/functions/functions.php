<?php

/**
 * Uniqueblocks
 */
\PoP\Root\App::addFilter('RequestUtils:getFramecomponentModules', 'getUserloginFramecomponentModules');
function getUserloginFramecomponentComponents($components)
{

    // // If the page is not cacheable, then we can already get the state of the logged in user
    // // Otherwise, this info will come from calling page LOGGEDINUSER_DATA from the webplatform
    // if (PoP_UserState_Utils::currentRouteRequiresUserState()) {
    $components[] = [PoP_Module_Processor_UserAccountGroups::class, PoP_Module_Processor_UserAccountGroups::COMPONENT_GROUP_LOGGEDINUSERDATA];
    // }
    return $components;
}
