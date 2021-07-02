<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Engine\Route\RouteUtils;
use PoP\ComponentModel\State\ApplicationState;

/**
 * login.php
 */
HooksAPIFacade::getInstance()->addFilter('UserPlatform:redirect_url:edit_profile', 'getCommonuserrolesEditprofileRedirectUrl');
function getCommonuserrolesEditprofileRedirectUrl($redirect_url)
{
    $vars = ApplicationState::getVars();
    if ($vars['global-userstate']['is-user-logged-in']) {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $current_user_id = $vars['global-userstate']['current-user-id'];
        if (gdUreIsOrganization($current_user_id)) {
            return RouteUtils::getRouteURL(POP_COMMONUSERROLES_ROUTE_EDITPROFILEORGANIZATION);
        } elseif (gdUreIsIndividual($current_user_id)) {
            return RouteUtils::getRouteURL(POP_COMMONUSERROLES_ROUTE_EDITPROFILEINDIVIDUAL);
        }
    }

    return $redirect_url;
}
