<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\UserRoles\Facades\UserRoleTypeDataResolverFacade;

define('GD_URE_ROLE_COMMUNITY', 'community');

HooksAPIFacade::getInstance()->addFilter('gdRoles', 'gdUreAddCommunityRole');
function gdUreAddCommunityRole($roles)
{
    $roles[] = GD_URE_ROLE_COMMUNITY;
    return $roles;
}

HooksAPIFacade::getInstance()->addFilter('getUserRoleCombinations', 'getUserRoleCombinationsCommunityRole');
function getUserRoleCombinationsCommunityRole($user_role_combinations)
{

    // 2 Combinations: a user may be a community or not
    $user_role_combinations = array(
        array(
            GD_ROLE_PROFILE,
        ),
        array(
            GD_ROLE_PROFILE,
            GD_URE_ROLE_COMMUNITY,
        ),
    );
    return $user_role_combinations;
}

function gdUreIsCommunity($user = null)
{
    $userRoleTypeDataResolver = UserRoleTypeDataResolverFacade::getInstance();
    return isProfile($user) && $userRoleTypeDataResolver->hasRole($user, GD_URE_ROLE_COMMUNITY);
}

function gdUreGetuserrole($userID)
{
    if (isProfile($userID)) {
        $role = GD_ROLE_PROFILE;
    } else {
        $userRoleTypeDataResolver = UserRoleTypeDataResolverFacade::getInstance();
        $roles = $userRoleTypeDataResolver->getUserRoles($userID);
        $role = $roles[0];
    }

    // Allow to return Organization/Individual roles
    return HooksAPIFacade::getInstance()->applyFilters(
        'gdUreGetuserrole',
        $role,
        $userID
    );
}



// Make sure we always get the most specific role
HooksAPIFacade::getInstance()->addFilter('UserTypeResolver:getValue:role', 'gdUreGetuserroleHook', 10, 2);
function gdUreGetuserroleHook($role, $user_id)
{
    return gdUreGetuserrole($user_id);
}

// Override the generic function with this one
HooksAPIFacade::getInstance()->addFilter('getTheUserRole', 'gdUreGetTheUserRole', 10, 2);
function gdUreGetTheUserRole($role, $user_id)
{
    return gdUreGetuserrole($user_id);
}
