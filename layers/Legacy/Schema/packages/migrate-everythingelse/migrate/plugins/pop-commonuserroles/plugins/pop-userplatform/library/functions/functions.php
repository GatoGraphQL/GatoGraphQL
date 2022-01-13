<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Route\RouteUtils;

/**
 * login.php
 */
\PoP\Root\App::getHookManager()->addFilter('UserPlatform:redirect_url:edit_profile', 'getCommonuserrolesEditprofileRedirectUrl');
function getCommonuserrolesEditprofileRedirectUrl($redirect_url)
{
    if (\PoP\Root\App::getState('is-user-logged-in')) {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $current_user_id = \PoP\Root\App::getState('current-user-id');
        if (gdUreIsOrganization($current_user_id)) {
            return RouteUtils::getRouteURL(POP_COMMONUSERROLES_ROUTE_EDITPROFILEORGANIZATION);
        } elseif (gdUreIsIndividual($current_user_id)) {
            return RouteUtils::getRouteURL(POP_COMMONUSERROLES_ROUTE_EDITPROFILEINDIVIDUAL);
        }
    }

    return $redirect_url;
}
