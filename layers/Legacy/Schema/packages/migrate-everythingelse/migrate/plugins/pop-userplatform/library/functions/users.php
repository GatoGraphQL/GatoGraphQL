<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoPSchema\UserRoles\Facades\UserRoleTypeAPIFacade;

function gdUserAttributes()
{
    return \PoP\Root\App::applyFilters('gdUserAttributes', array());
}

function gdGetUserattributes($user_id)
{
    return \PoP\Root\App::applyFilters('gdGetUserattributes', array(), $user_id);
}

/**
 * Determine the threshold capability to split admin users from website users
 */
function userHasAccess($capability, $user_id = null)
{
    if (is_null($user_id)) {
        $user_id = \PoP\Root\App::getState('current-user-id');
    }
    $userRoleTypeAPI = UserRoleTypeAPIFacade::getInstance();
    return $userRoleTypeAPI->userCan($user_id, $capability);
}

function userHasAdminAccess($user_id = null)
{
    return userHasAccess(NameResolverFacade::getInstance()->getName('popcms:capability:deletePages'), $user_id);
}
